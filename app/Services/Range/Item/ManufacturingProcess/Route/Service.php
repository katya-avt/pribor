<?php

namespace App\Services\Range\Item\ManufacturingProcess\Route;

use App\Models\Range\Route;
use App\Services\Range\Item\ManufacturingProcess\BaseService;

class Service extends BaseService
{

    protected function getFullModelName()
    {
        return Route::class;
    }

    protected function getRelationMethodName()
    {
        return 'routes';
    }
}
