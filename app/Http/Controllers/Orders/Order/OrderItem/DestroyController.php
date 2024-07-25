<?php

namespace App\Http\Controllers\Orders\Order\OrderItem;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Range\Item;
use App\Services\Orders\Order\OrderItem\ModifyService;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Order $order, Item $orderItem, ModifyService $service)
    {
        $message = $service->delete($order, $orderItem);

        return redirect()->route('orders.order-items.index', ['order' => $order->id])
            ->with('message', $message);
    }
}
