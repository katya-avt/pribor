<?php

namespace App\Http\Controllers\ChoiceModals\Range\Item\CurrentSpecificationsAndRoute\Specification;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class IndexController extends Controller
{
    public function __invoke(Item $item)
    {
        $itemSpecifications = $item->specifications;

        return view('choice-modals.range.item.current-specifications-and-route.specification.index',
            compact('itemSpecifications'));
    }
}
