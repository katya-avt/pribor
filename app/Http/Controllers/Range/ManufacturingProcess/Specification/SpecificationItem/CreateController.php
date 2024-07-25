<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Http\Controllers\Controller;
use App\Models\Range\Specification;
use function view;

class CreateController extends Controller
{
    public function __invoke(Specification $specification)
    {
        return view('range.manufacturing-process.specifications.specification-items.create',
            compact('specification'));
    }
}
