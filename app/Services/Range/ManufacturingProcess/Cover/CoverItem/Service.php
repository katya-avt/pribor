<?php

namespace App\Services\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Cover;
use App\Services\Range\ManufacturingProcess\BaseServiceForSpecificationItemCoverItem;

class Service extends BaseServiceForSpecificationItemCoverItem
{

    protected function getFullModelName()
    {
        return Cover::class;
    }
}
