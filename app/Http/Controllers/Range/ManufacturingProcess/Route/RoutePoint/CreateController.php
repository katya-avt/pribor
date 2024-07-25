<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Http\Controllers\Controller;
use App\Models\Range\Rate;
use App\Models\Range\Route;
use function view;

class CreateController extends Controller
{
    public function __invoke(Route $route)
    {
        $rates = Rate::all();

        return view('range.manufacturing-process.routes.route-points.create',
            compact('route', 'rates'));
    }
}
