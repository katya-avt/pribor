<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\ManufacturingProcess\Cover\StoreRequest;
use App\Services\Range\Item\ManufacturingProcess\Cover\Service;
use App\Models\Range\Item;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(Item $item, StoreRequest $request, Service $service)
    {
        $coverData = $request->validated();

        $message = $service->store($item, $coverData['number']);

        return redirect()->route('items.covers.index', ['item' => $item->id])
            ->with('message', $message);
    }
}
