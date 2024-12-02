<?php

namespace App\Services\External\Feegow;

use App\Services\External\Feegow\Endpoints\HasPatient;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

/**
 * Feegow Rapid Api Service
 * Doc: https://docs.feegow.com/
 */
class FeegowServiceApi {

    use HasPatient;

    public PendingRequest $api;

    public function __construct(){
        $this->api = Http::withHeaders([
            'x-access-token' => config('feegow.keys.access_token'),
        ])->baseUrl('https://api.feegow.com/v1/api');
    }
}
