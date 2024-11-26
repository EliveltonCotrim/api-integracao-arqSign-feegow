<?php

namespace App\Services\External\Feegow\Endpoints;

use App\Services\External\Feegow\Entities\Patient as PatientEntity;

class Patient extends BaseEndpoint
{
    public function searchPatient(string $patient_cpf): PatientEntity|null
    {
        $jsonPatient = $this->service->api->withQueryParameters(['paciente_cpf' => $patient_cpf])
            ->get('/patient/search/')
            ->json();

        return $jsonPatient['success'] ? $this->transform($jsonPatient['content'], PatientEntity::class) : null;
    }

    public function uploadFile(array $data)
    {
        $response = $this->service->api->post('/patient/upload-base64', [
            'paciente_id' => $data['paciente_id'],
            'cpf' => $data['cpf'],
            'nascimento' => $data['nascimento'],
            'base64_file' => $data['base64_file'],
            'arquivo_descricao' => $data['arquivo_descricao'],
        ]);

        return $response->json();
    }

}
