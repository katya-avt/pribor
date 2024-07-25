<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Route\StoreRequest;
use App\Services\Range\ManufacturingProcess\Route\Service;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, Service $service)
    {
        $routeNumber = $request->validated();

        $message = $service->store($routeNumber);

        return redirect()->route('routes.index')->with('message', $message);
    }
}
