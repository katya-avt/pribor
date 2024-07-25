<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Route\RoutePoint\UpdateRequest;
use App\Services\Range\ManufacturingProcess\Route\RoutePoint\Service;
use App\Models\Range\Route;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Route $route, $pointNumber, UpdateRequest $request, Service $service)
    {
        $newRoutePointData = $request->validated();

        $message = $service->update($route, $pointNumber, $newRoutePointData);

        return redirect()->route('routes.show', ['route' => $route->number])
            ->with('message', $message);
    }
}
