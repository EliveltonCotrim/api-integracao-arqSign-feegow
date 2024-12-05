<?php

namespace App\Services;

use App\Exceptions\FeegowException;
use App\Models\UploadFilesHistory;
use App\Services\External\Arqsign\Entities\ProcessWebhook;
use App\Services\External\Arqsign\Entities\WebhookNotification as EntitieWebhookNotification;
use App\Services\External\Feegow\Facades\FeegowApi;

class IntegracaoArqSignFeegowService
{
    public function dataProcessing(array $data)
    {
        $data = new EntitieWebhookNotification($data);
        $patients = [];

        foreach ($data->signatarios as $key => $signatario) {
            $patient_cpf = $signatario->dadosAssinatura?->dadosPessoaFisica?->numeroDocumentoPessoaFisica ?? null;

            $patients[$key] = FeegowApi::patient()->searchPatient($patient_cpf);
        }

        // Validar se encontrou todos sinatarios, se não encontrou, retornar erro
        throw_if(
            in_array(null, $patients, true),
            FeegowException::class,
            'Paciente não encontrado.'
        );

        $dataProcess = new ProcessWebhook($data);
        $processo = UploadFilesHistory::create($dataProcess->toArray());
        $processo->signatarios = $data->signatarios ?? null;
        $processo->save();

        foreach ($patients as $keyPatient => $patient) {
            $dataUploadFeegow = [
                'paciente_id' => $patient->id,
                'cpf' => $patient->cpf, // verificar se é pessoa fisica (DadosPessoaFisica) ou juridica (DadosPessoaJuridica)
                'nascimento' => $patient->nascimento,
            ];

            foreach ($data->documentos as $keyDoc => $documento) {

                $dataUploadFeegow['base64_file'] = $documento->base64Documento ?? null;
                $dataUploadFeegow['arquivo_descricao'] = $documento->nomeDocumento ?? null;

                $response = FeegowApi::patient()->uploadFile($dataUploadFeegow);
                // Verificar à necessidade de implementar fila.

                $errorMessage = $this->getMessages($response, $dataUploadFeegow);

                throw_if(
                    (isset($response['success']) && !$response['success']) || isset($response['base64_file']),
                    FeegowException::class,
                    $errorMessage,
                );

                logger()->channel('single')->info('Upload do arquivo concluído com sucesso.', ['paciente_id' => $dataUploadFeegow['paciente_id']]);
            }
        }

        $processo->documentos = array_map(function ($documento) {
            unset($documento->base64Documento);
            return $documento;
        }, $data->documentos) ?? null;

        $processo->statusProcesso = "Concluído";
        $processo->save();
    }

    public function getMessages(array $response, array $dataUploadFeegow): string
    {
        $messages = [];
        $errorMessage = 'Erro ao tentar fazer o upload do arquivo para o paciente: ' . $dataUploadFeegow['paciente_id'];

        if (!isset($response['success'])) {

            foreach ($response as $field => $errors) {
                if (is_array($errors)) {
                    foreach ($errors as $error) {
                        $messages[] = "{$field}: {$error}";
                    }
                }
            }

            $errorMessage = !empty($messages) ? implode('; ', $messages) : $errorMessage;
        }

        return $errorMessage;
    }
}
