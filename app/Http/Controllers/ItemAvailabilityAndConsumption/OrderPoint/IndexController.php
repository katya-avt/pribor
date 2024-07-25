<?php

namespace App\Http\Controllers\ItemAvailabilityAndConsumption\OrderPoint;

use App\Http\Controllers\Controller;
use App\Services\ItemAvailabilityAndConsumption\OrderPoint\Service;
use function view;

class IndexController extends Controller
{
    public function __invoke(Service $service)
    {
        $inOrderPurchasedItemsReachedItsOrderPoint = $service::getInOrderPurchasedItemsReachedItsOrderPoint();
        $outOfOrderPurchasedItemsReachedItsOrderPoint = $service::getOutOfOrderPurchasedItemsReachedItsOrderPoint();

        return view('item-availability-and-consumption.order-point.index',
            compact('inOrderPurchasedItemsReachedItsOrderPoint',
                'outOfOrderPurchasedItemsReachedItsOrderPoint'));
    }
}
