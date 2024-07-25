<?php

namespace App\Http\Controllers\ItemAvailabilityAndConsumption\Availability;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;

class EditController extends Controller
{
    public function __invoke(Item $item)
    {
        return view('item-availability-and-consumption.availability.edit', compact('item'));
    }
}
