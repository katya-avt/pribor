<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use function abort;

class PointNumber
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $route = $request->route('route');
        $pointNumber = $request->route('pointNumber');

        $pointNumbersArray = $route->points->pluck('pivot.point_number')->toArray();

        if (in_array($pointNumber, $pointNumbersArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
