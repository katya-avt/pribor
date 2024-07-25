<?php

namespace App\Http\Controllers\Orders\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\Order\UpdateRequest;
use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Services\Orders\Order\Service;

class UpdateController extends Controller
{
    public function __invoke(Order $order, UpdateRequest $request, Service $service)
    {
        $newOrderData = $request->validated();

        $message = $service->update($order, $newOrderData);

        return redirect()->route('orders.index', ['orderStatus' => OrderStatus::PENDING_URL_PARAM_NAME])
            ->with('message', $message);
    }
}
