<?php

namespace App\Http\Controllers\Orders\Order\OrderItem;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use App\Services\Orders\Order\OrderItem\ShowService;
use function view;

class IndexController extends Controller
{
    public function __invoke(Order $order, ShowService $service)
    {
        $orderItems = $order->items;
        $laborIntensityAndProfitDistribution = $service->getLaborIntensityAndProfitDistribution($order);

        return view('orders.orders.order-items.index',
            compact('order', 'orderItems', 'laborIntensityAndProfitDistribution'));
    }
}
