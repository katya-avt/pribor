<?php

namespace App\Services\Range\Item\Destroy;

use App\Models\Range\Specification;

class SpecificationModelService extends BaseService
{
    protected function getFullModelName()
    {
        return Specification::class;
    }
}
