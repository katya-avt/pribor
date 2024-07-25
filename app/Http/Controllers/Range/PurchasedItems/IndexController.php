<?php

namespace App\Http\Controllers\Range\PurchasedItems;

use App\Http\Controllers\Controller;
use App\Http\Filters\PurchasedItemFilter;
use App\Http\Requests\Range\PurchasedItems\IndexRequest;
use App\Models\Range\Item;
use function app;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(PurchasedItemFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItems = Item::has('purchasedItem')->with('purchasedItem')->filter($filter)->paginate(10);

        return view('range.purchased-items.index', compact('purchasedItems', 'data'));
    }
}
