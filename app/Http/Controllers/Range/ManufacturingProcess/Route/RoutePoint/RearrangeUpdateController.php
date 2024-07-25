<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Route\RoutePoint\RearrangeUpdateRequest;
use App\Services\Range\ManufacturingProcess\Route\RoutePoint\Service;
use App\Models\Range\Route;
use function redirect;

class RearrangeUpdateController extends Controller
{
    public function __invoke(Route $route, RearrangeUpdateRequest $request, Service $service)
    {
        $order = $request->validated();

        $message = $service->rearrange($route, $order['order']);

        return redirect()->route('routes.show', ['route' => $route->number])
            ->with('message', $message);
    }
}
