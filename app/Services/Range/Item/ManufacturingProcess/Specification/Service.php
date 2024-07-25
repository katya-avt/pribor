<?php

namespace App\Services\Range\Item\ManufacturingProcess\Specification;

use App\Models\Range\Specification;
use App\Services\Range\Item\ManufacturingProcess\BaseService;

class Service extends BaseService
{

    protected function getFullModelName()
    {
        return Specification::class;
    }

    protected function getRelationMethodName()
    {
        return 'specifications';
    }
}
