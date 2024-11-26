<?php

namespace App\Services;

use App\Exceptions\ArqSignWebhookException;
use App\Services\External\Arqsign\Entities\WebhookNotification as EntitieWebhookNotification;
use App\Services\External\Feegow\Facades\FeegowService;

class ArqSignService
{
    public function dataProcessing(array $data)
    {
        $data = new EntitieWebhookNotification($data);
        $patients = [];

        foreach ($data->signatarios as $key => $signatario) {
            $patient_cpf = $signatario->dadosAssinatura?->dadosPessoaFisica?->numeroDocumentoPessoaFisica ?? null;

            $patients[$key] = FeegowService::patient()->searchPatient($patient_cpf);
        }

        // Validar se encontrou todos sinatarios, se não encontrou, retornar erro
        throw_if(
            in_array(null, $patients, true),
            ArqSignWebhookException::class,
            'Paciente não encontrado.'
        );

        foreach ($patients as $key => $patient) {
            $dataUploadFeegow = [
                'paciente_id' => $patient->id,
                'cpf' => $patient->cpf, // verificar se é pessoa fisica (DadosPessoaFisica) ou juridica (DadosPessoaJuridica)
                'nascimento' => $patient->nascimento,
            ];

            foreach ($data->documentos as $key => $documento) {
                $dataUploadFeegow['base64_file'] = $documento->base64Documento;
                $dataUploadFeegow['arquivo_descricao'] = $documento->nomeDocumento;

                $response = FeegowService::patient()->uploadFile($dataUploadFeegow);
                // Verificar à necessidade de implementar fila.

                if (isset($response['success']) && !$response['success']) {
                    logger()->error('Erro ao tentar fazer o upload do arquivo.', ['paciente_id' => $dataUploadFeegow['paciente_id'], 'cpf' => $dataUploadFeegow['cpf']]);
                } else {
                    logger()->info('Upload do arquivo concluído com sucesso.', ['paciente_id' => $dataUploadFeegow['paciente_id'], 'cpf' => $dataUploadFeegow['cpf']]);
                }
            }
        }

    }
}
