<?php

namespace App\Http\Controllers\Orders\Order;

use App\Http\Controllers\Controller;
use function view;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('orders.orders.create');
    }
}
