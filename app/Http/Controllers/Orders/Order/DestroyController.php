<?php

namespace App\Http\Controllers\Orders\Order;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Services\Orders\Order\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Order $order, Service $service)
    {
        $message = $service->delete($order);

        return redirect()->route('orders.index', ['orderStatus' => OrderStatus::PENDING_URL_PARAM_NAME])
            ->with('message', $message);
    }
}
