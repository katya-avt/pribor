<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use App\Models\Range\Specification as SpecificationModel;
use function abort;

class Specification
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
        $specification = $request->route('specification');

        $specificationsArray = SpecificationModel::pluck('number')->toArray();

        if (in_array($specification->number, $specificationsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
