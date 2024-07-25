<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\ManufacturingProcess\Route\StoreRequest;
use App\Services\Range\Item\ManufacturingProcess\Route\Service;
use App\Models\Range\Item;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(Item $item, StoreRequest $request, Service $service)
    {
        $routeData = $request->validated();

        $message = $service->store($item, $routeData['number']);

        return redirect()->route('items.routes.index', ['item' => $item->id])
            ->with('message', $message);
    }
}
