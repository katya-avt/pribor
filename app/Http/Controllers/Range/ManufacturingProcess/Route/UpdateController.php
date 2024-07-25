<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Route\UpdateRequest;
use App\Services\Range\ManufacturingProcess\Route\Service;
use App\Models\Range\Route;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Route $route, UpdateRequest $request, Service $service)
    {
        $newRouteNumber = $request->validated();

        $message = $service->update($route, $newRouteNumber);

        return redirect()->route('routes.index')->with('message', $message);
    }
}
