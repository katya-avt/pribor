<?php

namespace App\Http\Controllers\ItemAvailabilityAndConsumption\Consumption;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItemSpecification;

class ShowController extends Controller
{
    public function __invoke(Order $order)
    {
        $orderItems = OrderItemSpecification::where('order_id', $order->id)
            ->whereNull('order_item_specification_parent_id')
            ->with('orderItem')
            ->with('descendants')
            ->get()
            ->groupBy('item_id');

        $orderItemWithoutDetailsIdsCollection = $order->items->pluck('id')->diff($orderItems->keys());

        return view('item-availability-and-consumption.consumption.show',
            compact('order', 'orderItems', 'orderItemWithoutDetailsIdsCollection'));
    }
}
