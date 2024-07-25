<?php

namespace App\Http\Controllers\PeriodicRequisites\PurchasePrice;

use App\Http\Controllers\Controller;
use App\Http\Filters\PurchasedItemFilter;
use App\Models\Range\Item;
use App\Http\Requests\PeriodicRequisites\PurchasePrice\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(PurchasedItemFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItems = Item::has('purchasedItem')->with('purchasedItem')->filter($filter)->paginate(10);

        return view('periodic-requisites.purchase-price.index',
            compact('purchasedItems', 'data'));
    }
}
