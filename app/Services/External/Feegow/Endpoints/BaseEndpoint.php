<?php

namespace App\Services\External\Feegow\Endpoints;

use App\Services\External\Feegow\FeegowService;


class BaseEndpoint
{
    protected FeegowService $service;

    public function __construct()
    {
        $this->service = new FeegowService();
    }

    protected function transform(mixed $json, string $entity)
    {
        return new $entity($json);
    }
}
