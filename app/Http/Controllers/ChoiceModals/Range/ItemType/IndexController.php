<?php

namespace App\Http\Controllers\ChoiceModals\Range\ItemType;

use App\Http\Controllers\Controller;
use App\Models\Range\ItemType;

class IndexController extends Controller
{
    public function __invoke()
    {
        $itemTypes = ItemType::all();
        return view('choice-modals.range.item-type.index', compact('itemTypes'));
    }
}
