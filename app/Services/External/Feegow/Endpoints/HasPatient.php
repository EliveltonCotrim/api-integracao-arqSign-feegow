<?php

namespace App\Services\External\Feegow\Endpoints;

trait HasPatient {
    public function patient()
    {
        return new Patient();
    }
}
