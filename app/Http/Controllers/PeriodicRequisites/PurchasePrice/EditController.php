<?php

namespace App\Http\Controllers\PeriodicRequisites\PurchasePrice;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;

class EditController extends Controller
{
    public function __invoke(Item $item)
    {
        return view('periodic-requisites.purchase-price.edit', compact('item'));
    }
}
