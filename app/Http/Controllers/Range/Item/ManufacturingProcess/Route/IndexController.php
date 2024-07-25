<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class IndexController extends Controller
{
    public function __invoke(Item $item)
    {
        $itemRoutes = $item->routes;

        return view('range.items.manufacturing-process.routes.index',
            compact('item', 'itemRoutes'));
    }
}
