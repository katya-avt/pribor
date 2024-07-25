<?php

namespace App\Http\Controllers\ItemAvailabilityAndConsumption\Availability;

use App\Http\Controllers\Controller;
use App\Http\Requests\ItemAvailabilityAndConsumption\Availability\UpdateRequest;
use App\Models\Range\Item;
use App\Services\ItemAvailabilityAndConsumption\Availability\Service;

class UpdateController extends Controller
{
    public function __invoke(Item $item, UpdateRequest $request, Service $service)
    {
        $newCnt = $request->validated();

        $message = $service->update($item, $newCnt);

        return redirect()->route('item-availability-and-consumption.availability.index')
            ->with('message', $message);
    }
}
