<?php

namespace App\Http\Controllers\Range\Item\CurrentSpecificationsAndRoute;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class EditController extends Controller
{
    public function __invoke(Item $item)
    {
        return view('range.items.current-specifications-and-route.edit', compact('item'));
    }
}
