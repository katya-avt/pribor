<?php

namespace App\Http\Controllers\Range\Item;

use App\Http\Controllers\Controller;
use App\Http\Filters\ItemFilter;
use App\Http\Requests\Range\Item\IndexRequest;
use App\Models\Range\Item;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(ItemFilter::class, ['queryParams' => array_filter($data)]);

        $items = Item::with('group', 'itemType', 'unit', 'manufactureType')->filter($filter)
            ->paginate(10);

        return view('range.items.index', compact('items', 'data'));
    }
}
