<?php

namespace App\Http\Controllers\Orders\Order\OrderItem;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;
use function view;

class CreateController extends Controller
{
    public function __invoke(Order $order)
    {
        return view('orders.orders.order-items.create', compact('order'));
    }
}
