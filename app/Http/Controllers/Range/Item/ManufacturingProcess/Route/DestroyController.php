<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use App\Services\Range\Item\ManufacturingProcess\Route\Service;
use App\Models\Range\Route;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Item $item, Route $route, Service $service)
    {
        $message = $service->delete($item, $route);

        return redirect()->route('items.routes.index', ['item' => $item->id])
            ->with('message', $message);
    }
}
