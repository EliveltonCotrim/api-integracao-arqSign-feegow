<?php

namespace App\Services;

use App\Enums\ProcessUploadFeegowStatusEnum;
use App\Exceptions\FeegowException;
use App\Models\UploadFilesHistory;
use App\Services\External\Arqsign\Entities\ProcessWebhook;
use App\Services\External\Arqsign\Entities\WebhookNotification as EntitieWebhookNotification;
use App\Services\External\Feegow\Entities\Patient;
use App\Services\External\Feegow\Facades\FeegowApi;

class IntegracaoArqSignFeegowService
{

    public function dataProcessing(array $data)
    {
        $data = new EntitieWebhookNotification($data);

        if (empty($data->signatarios) || empty($data->documentos)) {
            throw new FeegowException(message: 'Dados insuficientes para processamento.');
        }

        // Busca os pacientes no Feegow pelo CPF
        $patients = collect($data->signatarios)
            ->filter(fn($signatario) => in_array("Contratante (Paciente)", $signatario->papelSignatario?->pessoaFisica ?? []))
            ->map(function ($signatario, $key) {
                $patient_cpf = $signatario->dadosAssinatura->dadosPessoaFisica->numeroDocumentoPessoaFisica ?? null;

                if ($patient_cpf) {
                    return FeegowApi::patient()->searchPatient($patient_cpf);
                }

                return new Patient([]);
            });

        // Verifica se algum paciente não foi encontrado
        if ($patients->contains('empty', '==', true)) {
            throw new FeegowException('Pacientes não encontrado.');
        }

        $processoData = (new ProcessWebhook($data))->toArray();
        $processoData['signatarios'] = $data->signatarios ?? null;
        $processoData['statusProcesso'] = "Concluído";

        $processo = UploadFilesHistory::create($processoData);

        foreach ($patients as $patient) {
            foreach ($data->documentos as $documento) {

                // Prepera os dados para o upload do arquivo
                $dataUploadFeegow = $this->processDocument($patient, $documento);

                // Faz o upload do arquivo
                $response = FeegowApi::patient()->uploadFile($dataUploadFeegow);

                // Obtem as mensagens de retorno
                $errorMessage = $this->getMessages($response, $dataUploadFeegow);

                throw_if(
                    (isset($response['success']) && !$response['success']) || isset($response['base64_file']),
                    FeegowException::class,
                    $errorMessage,
                );

                $dataUploadFeegow['upload_status'] = ProcessUploadFeegowStatusEnum::SUCCESS->value;

                $processo->feegowUploadProcess()->create($dataUploadFeegow);

                logger()->channel('single')->info('Upload do arquivo concluído com sucesso.', ['paciente_id' => $dataUploadFeegow['paciente_id']]);

                // Fila para processar o upload do arquivo
                // UploadFile::dispatch($dataUploadFeegow, $processo);
            }
        }

        $processo->documentos = collect($data->documentos)
            ->map(function ($documento) {
                unset($documento->base64Documento);
                return $documento;
            })->toArray();

        $processo->statusProcesso = "Concluído";
        $processo->save();
    }

    protected function processDocument($patient, $documento)
    {
        return [
            'paciente_id' => $patient->id,
            'cpf' => $patient->cpf,
            'nascimento' => now()->format('Y-m-d', $patient->nascimento),
            'base64_file' => $documento->base64Documento ?? null,
            'arquivo_descricao' => $documento->nomeDocumento ?? null,
        ];
    }

    protected function getMessages(array $response, array $dataUploadFeegow): string
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
