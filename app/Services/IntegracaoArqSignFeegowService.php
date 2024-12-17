<?php

namespace App\Services;

use App\Exceptions\FeegowException;
use App\Jobs\UploadFile;
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

        // Verificar se encontrou o paciente
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

                logger()->info('dataUploadFeegow', ['dataUploadFeegow' => $dataUploadFeegow]);

                // Fila para processar o upload do arquivo
                UploadFile::dispatch($dataUploadFeegow, $processo);
            }
        }

        $processo->documentos = array_map(function ($documento) {
            unset($documento->base64Documento);
            return $documento;
        }, $data->documentos) ?? null;

        $processo->statusProcesso = "Concluído";
        $processo->save();
    }
}
