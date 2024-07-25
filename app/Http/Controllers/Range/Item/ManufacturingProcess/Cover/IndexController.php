<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class IndexController extends Controller
{
    public function __invoke(Item $item)
    {
        $itemCovers = $item->covers;

        return view('range.items.manufacturing-process.covers.index',
            compact('item', 'itemCovers'));
    }
}
