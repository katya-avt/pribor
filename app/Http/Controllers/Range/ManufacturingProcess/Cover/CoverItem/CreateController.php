<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use function view;

class CreateController extends Controller
{
    public function __invoke(Cover $cover)
    {
        return view('range.manufacturing-process.covers.cover-items.create', compact('cover'));
    }
}
