<?php

namespace App\Http\Controllers\Admin\MarkedForDeletion\Item;

use App\Http\Controllers\Controller;
use App\Http\Filters\ItemFilter;
use App\Http\Requests\Admin\MarkedForDeletion\Item\IndexRequest;
use App\Models\Range\Item;
use function app;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(ItemFilter::class, ['queryParams' => array_filter($data)]);

        $items = Item::onlyTrashed()->filter($filter)->paginate(10);

        return view('admin.marked-for-deletion.items.index', compact('items', 'data'));
    }
}
