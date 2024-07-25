<?php

namespace App\Http\Controllers\Range\Item;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;

class EditController extends Controller
{
    public function __invoke(Item $item)
    {
        return view('range.items.edit', compact('item'));
    }
}
