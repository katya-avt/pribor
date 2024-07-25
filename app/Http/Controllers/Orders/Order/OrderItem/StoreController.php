<?php

namespace App\Http\Controllers\Orders\Order\OrderItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\Order\OrderItem\StoreRequest;
use App\Models\Orders\Order;
use App\Services\Orders\Order\OrderItem\ModifyService;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(Order $order, StoreRequest $request, ModifyService $service)
    {
        $orderItemData = $request->validated();

        $message = $service->store($order, $orderItemData);

        return redirect()->route('orders.order-items.index', ['order' => $order->id])
            ->with('message', $message);
    }
}
