<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use function view;

class EditController extends Controller
{
    public function __invoke(Cover $cover)
    {
        return view('range.manufacturing-process.covers.edit', compact('cover'));
    }
}
