<?php

namespace App\Http\Controllers\Orders\Order;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Services\Orders\Order\Service;
use App\Models\Orders\OrderStatus;

class CompleteProductionController extends Controller
{
    public function __invoke(Order $order, Service $service)
    {
        $message = $service->completeProduction($order);

        return redirect()
            ->route('orders.index', ['orderStatus' => OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME])
            ->with('message', $message);
    }
}
