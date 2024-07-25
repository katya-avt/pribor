<?php

namespace App\Http\Controllers\Orders\Order\OrderItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\Order\OrderItem\UpdateRequest;
use App\Models\Orders\Order;
use App\Models\Range\Item;
use App\Services\Orders\Order\OrderItem\ModifyService;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Order $order, Item $orderItem, UpdateRequest $request, ModifyService $service)
    {
        $newOrderItemData = $request->validated();

        $message = $service->update($order, $orderItem, $newOrderItemData);

        return redirect()->route('orders.order-items.index', ['order' => $order->id])
            ->with('message', $message);
    }
}
