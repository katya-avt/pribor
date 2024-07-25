<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Http\Controllers\Controller;
use App\Models\Range\Route;
use function view;

class RearrangeController extends Controller
{
    public function __invoke(Route $route)
    {
        $pointsOrder = $route->points->pluck('pivot.point_code', 'pivot.point_number');
        $pointCount = $route->points()->count();

        return view('range.manufacturing-process.routes.route-points.rearrange',
            compact('route', 'pointsOrder', 'pointCount'));
    }
}
