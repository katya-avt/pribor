<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\ManufacturingProcess\Specification\StoreRequest;
use App\Services\Range\Item\ManufacturingProcess\Specification\Service;
use App\Models\Range\Item;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(Item $item, StoreRequest $request, Service $service)
    {
        $specificationData = $request->validated();

        $message = $service->store($item, $specificationData['number']);

        return redirect()->route('items.specifications.index', ['item' => $item->id])
            ->with('message', $message);
    }
}
