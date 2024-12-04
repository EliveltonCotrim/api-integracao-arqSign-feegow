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

        foreach ($patients as $keyPatient => $patient) {
            $dataUploadFeegow = [
                'paciente_id' => $patient->id,
                'cpf' => $patient->cpf, // verificar se é pessoa fisica (DadosPessoaFisica) ou juridica (DadosPessoaJuridica)
                'nascimento' => $patient->nascimento,
            ];

            $processo->signatarios()->create([
                'feegow_id_paciente' => $dataUploadFeegow['paciente_id'],
                'feegow_nascimento' => now()->parse($dataUploadFeegow['nascimento']),
                'feegow_cpf' => $dataUploadFeegow['cpf'],
                'ordemAssinatura' => $data?->signatarios[$keyPatient]?->ordemAssinatura,
                'idSignatario' => $data?->signatarios[$keyPatient]?->id,
                'nome' => $data?->signatarios[$keyPatient]?->nome,
                'email' => $data?->signatarios[$keyPatient]?->email ?? null,
                'telefone' => $data?->signatarios[$keyPatient]?->telefone ?? null,
                'pessoaFisica' => $data?->signatarios[$keyPatient]?->dadosAssinatura?->dadosPessoaFisica->tipoDocumentoPessoaFisica ? true : false,
                'tipoAssinatura' => $data?->signatarios[$keyPatient]?->dadosAssinatura?->tipoAssinatura,
                'dataAssinatura' => $data?->signatarios[$keyPatient]?->dadosAssinatura?->dataAssinatura,
                'geoLocalizacao' => $data?->signatarios[$keyPatient]?->dadosAssinatura?->geoLocalizacao ?? null,
                'ip' => $data?->signatarios[$keyPatient]?->dadosAssinatura?->ip ?? null,
                'tipoDocumentoPessoaFisica' => $data?->signatarios[$keyPatient]?->dadosAssinatura?->dadosPessoaFisica->tipoDocumentoPessoaFisica ?? null,
                'numeroDocumetoPessoaFisica' => $data?->signatarios[$keyPatient]?->dadosAssinatura?->dadosPessoaFisica->numeroDocumentoPessoaFisica ?? null,
            ]);

            foreach ($data->documentos as $keyDoc => $documento) {

                $dataUploadFeegow['base64_file'] = $documento->base64Documento ?? null;
                $dataUploadFeegow['arquivo_descricao'] = $documento->nomeDocumento ?? null;

                $response = FeegowApi::patient()->uploadFile($dataUploadFeegow);
                // Verificar à necessidade de implementar fila.

                $errorMessage = $this->getMessages($response, $dataUploadFeegow);

                // if(!$processo->documentos->where('id_documento')->first()->id_documento == $data?->documentos[$keyDoc]?->id) {
                    $processo->documentos()->create([
                        'id_documento' => $data?->documentos[$keyDoc]?->id,
                        'ordemDocumento' => $data?->documentos[$keyDoc]?->ordemDocumento,
                        'nomeDocumento' => $data?->documentos[$keyDoc]?->nomeDocumento,
                    ]);
                // }

                throw_if(
                    (isset($response['success']) && !$response['success']) || isset($response['base64_file']),
                    FeegowException::class,
                    $errorMessage,
                );

                logger()->channel('single')->info('Upload do arquivo concluído com sucesso.', ['paciente_id' => $dataUploadFeegow['paciente_id']]);
            }
        }

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
