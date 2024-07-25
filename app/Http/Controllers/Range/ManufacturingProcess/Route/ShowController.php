<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Models\Range\Route;
use function view;

class ShowController extends Controller
{
    public function __invoke(Route $route)
    {
        $routeData = $route->load('points', 'operations');

        return view('range.manufacturing-process.routes.show',
            compact('route', 'routeData'));
    }
}
