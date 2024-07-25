<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Models\Range\Route;
use App\Services\Range\ManufacturingProcess\Route\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Route $route, Service $service)
    {
        $message = $service->delete($route);

        return redirect()->route('routes.index')->with('message', $message);
    }
}
