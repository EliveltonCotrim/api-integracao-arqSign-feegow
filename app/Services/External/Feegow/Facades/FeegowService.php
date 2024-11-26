<?php

namespace App\Services\External\Feegow\Facades;

use App\Services\External\Feegow\Endpoints\Patient;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Patient Patient()
 */
class FeegowService extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\External\Feegow\FeegowService::class;
    }
}
