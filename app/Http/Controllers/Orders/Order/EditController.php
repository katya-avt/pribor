<?php

namespace App\Http\Controllers\Orders\Order;

use App\Http\Controllers\Controller;
use App\Models\Orders\Order;

class EditController extends Controller
{
    public function __invoke(Order $order)
    {
        return view('orders.orders.edit', compact('order'));
    }
}
