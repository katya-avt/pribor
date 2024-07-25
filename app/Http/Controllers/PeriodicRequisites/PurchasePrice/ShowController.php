<?php

namespace App\Http\Controllers\PeriodicRequisites\PurchasePrice;

use App\Http\Controllers\Controller;
use App\Http\Filters\FilterConstants;
use App\Http\Filters\PurchasedItemPurchasePriceHistoryFilter;
use App\Models\Range\Item;
use App\Http\Requests\PeriodicRequisites\PurchasePrice\ShowRequest;
use App\Models\Range\PurchasedItem;
use function view;

class ShowController extends Controller
{
    public function __invoke(Item $item, ShowRequest $request)
    {
        $data = $request->validated();

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $itemWithPurchaseData = $item->load('purchasedItem');

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChanges = $purchasedItem->purchasePriceChanges()->filter($filter)->get();

        $values = $purchasedItemPurchasePriceChanges->pluck('new_value')->toArray();
        $timePoints = $purchasedItemPurchasePriceChanges->pluck('change_time')->toArray();

        $periods = FilterConstants::PERIODS;

        return view('periodic-requisites.purchase-price.show',
            compact('itemWithPurchaseData', 'values', 'timePoints', 'periods', 'data'));
    }
}
