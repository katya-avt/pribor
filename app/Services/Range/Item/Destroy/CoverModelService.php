<?php

namespace App\Services\Range\Item\Destroy;

use App\Models\Range\Cover;

class CoverModelService extends BaseService
{
    protected function getFullModelName()
    {
        return Cover::class;
    }
}
