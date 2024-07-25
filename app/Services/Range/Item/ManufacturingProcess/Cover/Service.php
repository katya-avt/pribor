<?php

namespace App\Services\Range\Item\ManufacturingProcess\Cover;

use App\Models\Range\Cover;
use App\Services\Range\Item\ManufacturingProcess\BaseService;

class Service extends BaseService
{

    protected function getFullModelName()
    {
        return Cover::class;
    }

    protected function getRelationMethodName()
    {
        return 'covers';
    }
}
