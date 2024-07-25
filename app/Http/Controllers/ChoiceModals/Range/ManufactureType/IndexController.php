<?php

namespace App\Http\Controllers\ChoiceModals\Range\ManufactureType;

use App\Http\Controllers\Controller;
use App\Models\Range\ManufactureType;

class IndexController extends Controller
{
    public function __invoke()
    {
        $manufactureTypes = ManufactureType::all();
        return view('choice-modals.range.manufacture-type.index', compact('manufactureTypes'));
    }
}
