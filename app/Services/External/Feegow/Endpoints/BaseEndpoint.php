<?php

namespace App\Services\External\Feegow\Endpoints;

use App\Services\External\Feegow\FeegowServiceApi;


class BaseEndpoint
{
    protected FeegowServiceApi $feegowServiceApi;

    public function __construct()
    {
        $this->feegowServiceApi = new FeegowServiceApi();
    }

    protected function transform(mixed $json, string $entity)
    {
        return new $entity($json);
    }
}
