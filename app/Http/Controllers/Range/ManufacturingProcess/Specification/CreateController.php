<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use function view;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('range.manufacturing-process.specifications.create');
    }
}
