<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use App\Models\Range\Point as PointModel;
use function abort;

class Point
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
        $point = $request->route('point');

        $pointsArray = PointModel::pluck('code')->toArray();

        if (in_array($point->code, $pointsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
