<?php

namespace App\Services\Range\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Services\Range\ManufacturingProcess\BaseServiceForSpecificationCoverRoute;

class Service extends BaseServiceForSpecificationCoverRoute
{

    protected function getFullModelName()
    {
        return Cover::class;
    }

    protected function getRelationMethodName()
    {
        return 'items';
    }
}
