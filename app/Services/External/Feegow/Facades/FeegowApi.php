<?php

namespace App\Services\External\Feegow\Facades;

use App\Services\External\Feegow\Endpoints\Patient;
use Illuminate\Support\Facades\Facade;

/**
 * @method static Patient Patient()
 */
class FeegowApi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \App\Services\External\Feegow\FeegowServiceApi::class;
    }
}
