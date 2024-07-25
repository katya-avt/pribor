<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Models\Range\Specification;
use function view;

class EditController extends Controller
{
    public function __invoke(Specification $specification)
    {
        return view('range.manufacturing-process.specifications.edit', compact('specification'));
    }
}
