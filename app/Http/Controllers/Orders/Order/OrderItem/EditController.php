<?php

namespace App\Http\Controllers\Orders\Order\OrderItem;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Range\Item;
use function view;

class EditController extends Controller
{
    public function __invoke(Order $order, Item $orderItem)
    {
        $item = $order->items()->where('id', $orderItem->id)->first();

        return view('orders.orders.order-items.edit', compact('order', 'item'));
    }
}
