<?php

namespace App\Http\Controllers\Range\PurchasedItems;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\PurchasedItems\UpdateRequest;
use App\Models\Range\Item;
use App\Services\Range\PurchasedItems\Service;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Item $item, UpdateRequest $request, Service $service)
    {
        $newData = $request->validated();

        $message = $service->update($item, $newData);

        return redirect()->route('purchased-items.index')->with('message', $message);
    }
}
