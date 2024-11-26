<?php

namespace App\Services\External\Feegow\Endpoints;

trait SearchPatient {
    public function patient()
    {
        return new Patient();
    }
}
