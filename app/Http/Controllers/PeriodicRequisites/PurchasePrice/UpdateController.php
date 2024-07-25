<?php

namespace App\Http\Controllers\PeriodicRequisites\PurchasePrice;

use App\Http\Controllers\Controller;
use App\Http\Requests\PeriodicRequisites\PurchasePrice\UpdateRequest;
use App\Models\Range\Item;
use App\Services\PeriodicRequisites\PurchasePrice\Service;

class UpdateController extends Controller
{
    public function __invoke(Item $item, UpdateRequest $request, Service $service)
    {
        $newPurchasePrice = $request->validated();

        $message = $service->update($item, $newPurchasePrice);

        return redirect()->route('periodic-requisites.purchase-price.index')->with('message', $message);
    }
}
