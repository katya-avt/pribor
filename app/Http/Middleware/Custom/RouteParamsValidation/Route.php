<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use App\Models\Range\Route as RouteModel;
use function abort;

class Route
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

        $routesArray = RouteModel::pluck('number')->toArray();

        if (in_array($route->number, $routesArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
