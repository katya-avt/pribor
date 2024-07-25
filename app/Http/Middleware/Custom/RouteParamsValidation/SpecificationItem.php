<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use function abort;

class SpecificationItem
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
        $specificationItem = $request->route('specificationItem');

        $specificationItemsArray = $specification->items->pluck('id')->toArray();

        if (in_array($specificationItem->id, $specificationItemsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
