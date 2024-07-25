<?php

namespace App\Http\Controllers\ChoiceModals\Orders\Customer;

use App\Http\Controllers\Controller;
use App\Models\Orders\Customer;

class IndexController extends Controller
{
    public function __invoke()
    {
        $customers = Customer::all();
        return view('choice-modals.orders.customer.index', compact('customers'));
    }
}
