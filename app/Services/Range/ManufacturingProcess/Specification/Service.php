<?php

namespace App\Services\Range\ManufacturingProcess\Specification;

use App\Models\Range\Specification;
use App\Services\Range\ManufacturingProcess\BaseServiceForSpecificationCoverRoute;

class Service extends BaseServiceForSpecificationCoverRoute
{

    protected function getFullModelName()
    {
        return Specification::class;
    }

    protected function getRelationMethodName()
    {
        return 'items';
    }
}
