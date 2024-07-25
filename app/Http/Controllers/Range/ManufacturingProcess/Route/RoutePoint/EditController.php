<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Http\Controllers\Controller;
use App\Models\Range\Rate;
use App\Models\Range\Route;
use function view;

class EditController extends Controller
{
    public function __invoke(Route $route, $pointNumber)
    {
        $routePointData = $route->points()->wherePivot('point_number', $pointNumber)->first();
        $rates = Rate::all();

        return view('range.manufacturing-process.routes.route-points.edit',
            compact('route', 'pointNumber', 'routePointData', 'rates'));
    }
}
