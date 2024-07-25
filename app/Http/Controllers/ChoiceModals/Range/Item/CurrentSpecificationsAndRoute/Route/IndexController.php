<?php

namespace App\Http\Controllers\ChoiceModals\Range\Item\CurrentSpecificationsAndRoute\Route;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class IndexController extends Controller
{
    public function __invoke(Item $item)
    {
        $itemRoutes = $item->routes;

        return view('choice-modals.range.item.current-specifications-and-route.route.index',
            compact('itemRoutes'));
    }
}
