<?php

namespace App\Http\Controllers\Orders\Order;

use App\Http\Controllers\Controller;
use App\Http\Requests\Orders\Order\StoreRequest;
use App\Models\Orders\OrderStatus;
use App\Services\Orders\Order\Service;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, Service $service)
    {
        $orderData = $request->validated();

        $message = $service->store($orderData);

        return redirect()->route('orders.index', ['orderStatus' => OrderStatus::PENDING_URL_PARAM_NAME])
            ->with('message', $message);
    }
}
