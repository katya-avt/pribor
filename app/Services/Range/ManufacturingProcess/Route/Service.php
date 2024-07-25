<?php

namespace App\Services\Range\ManufacturingProcess\Route;

use App\Models\Range\Route;
use App\Services\Range\ManufacturingProcess\BaseServiceForSpecificationCoverRoute;

class Service extends BaseServiceForSpecificationCoverRoute
{

    protected function getFullModelName()
    {
        return Route::class;
    }

    protected function getRelationMethodName()
    {
        return 'points';
    }
}
