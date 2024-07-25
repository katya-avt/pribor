<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Route\RoutePoint\StoreRequest;
use App\Services\Range\ManufacturingProcess\Route\RoutePoint\Service;
use App\Models\Range\Route;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(Route $route, StoreRequest $request, Service $service)
    {
        $newRoutePointData = $request->validated();

        $message = $service->store($route, $newRoutePointData);

        return redirect()->route('routes.show', ['route' => $route->number])
            ->with('message', $message);
    }
}
