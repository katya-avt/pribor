<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class IndexController extends Controller
{
    public function __invoke(Item $item)
    {
        $itemSpecifications = $item->specifications;

        return view('range.items.manufacturing-process.specifications.index',
            compact('item', 'itemSpecifications'));
    }
}
