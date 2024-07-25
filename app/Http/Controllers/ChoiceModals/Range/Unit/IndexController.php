<?php

namespace App\Http\Controllers\ChoiceModals\Range\Unit;

use App\Http\Controllers\Controller;
use App\Models\Range\Unit;

class IndexController extends Controller
{
    public function __invoke()
    {
        $units = Unit::all();
        return view('choice-modals.range.unit.index', compact('units'));
    }
}
