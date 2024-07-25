<?php

namespace App\Http\Controllers\ChoiceModals\Range\Item\CurrentSpecificationsAndRoute\Cover;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class IndexController extends Controller
{
    public function __invoke(Item $item)
    {
        $itemCovers = $item->covers;

        return view('choice-modals.range.item.current-specifications-and-route.cover.index',
            compact('itemCovers'));
    }
}
