<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use function view;

class ShowController extends Controller
{
    public function __invoke(Cover $cover)
    {
        $coverData = $cover->items->load('group', 'unit');

        return view('range.manufacturing-process.covers.show',
            compact('cover', 'coverData'));
    }
}
