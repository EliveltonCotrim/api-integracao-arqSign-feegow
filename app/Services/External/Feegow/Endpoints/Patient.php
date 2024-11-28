<?php

namespace App\Services\External\Feegow\Endpoints;

use App\Services\External\Feegow\Entities\Patient as PatientEntity;
use App\Services\External\Feegow\Entities\UploadFileData;

class Patient extends BaseEndpoint
{
    public function searchPatient(string $patient_cpf): PatientEntity|null
    {
        $jsonPatient = $this->feegowServiceApi->api->withQueryParameters(['paciente_cpf' => $patient_cpf])
            ->get('/patient/search/')
            ->json();

        return $jsonPatient['success'] ? $this->transform($jsonPatient['content'], PatientEntity::class) : null;
    }

    /**
     * Summary of uploadFile
     * @param array $data
     * @return array
     */
    public function uploadFile(array $data): array
    {
        $response = $this->feegowServiceApi->api->post('/patient/upload-base64', [...$data]);

        return $response->json();
    }

}
