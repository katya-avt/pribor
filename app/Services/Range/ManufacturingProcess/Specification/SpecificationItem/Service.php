<?php

namespace App\Services\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Models\Range\Specification;
use App\Services\Range\ManufacturingProcess\BaseServiceForSpecificationItemCoverItem;

class Service extends BaseServiceForSpecificationItemCoverItem
{

    protected function getFullModelName()
    {
        return Specification::class;
    }
}
