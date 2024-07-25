<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Http\Controllers\Controller;
use App\Models\Range\Route;
use App\Services\Range\ManufacturingProcess\Route\RoutePoint\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Route $route, $pointNumber, Service $service)
    {
        $message = $service->delete($route, $pointNumber);

        return redirect()->route('routes.show', ['route' => $route->number])
            ->with('message', $message);
    }
}
