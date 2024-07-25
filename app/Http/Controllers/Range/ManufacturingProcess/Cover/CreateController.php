<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use function view;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('range.manufacturing-process.covers.create');
    }
}
