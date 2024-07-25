<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Models\Range\Specification;
use function view;

class ShowController extends Controller
{
    public function __invoke(Specification $specification)
    {
        $specificationData = $specification->items->load('group', 'unit');

        return view('range.manufacturing-process.specifications.show',
            compact('specification', 'specificationData'));
    }
}
