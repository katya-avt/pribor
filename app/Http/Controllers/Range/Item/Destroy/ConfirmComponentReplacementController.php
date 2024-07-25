<?php

namespace App\Http\Controllers\Range\Item\Destroy;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class ConfirmComponentReplacementController extends Controller
{
    public function __invoke(Item $item)
    {
        return view('range.items.destroy.confirm-component-replacement', compact('item'));
    }
}
