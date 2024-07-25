<?php

namespace App\Http\Controllers\Range\PurchasedItems;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class EditController extends Controller
{
    public function __invoke(Item $item)
    {
        return view('range.purchased-items.edit', compact('item'));
    }
}
