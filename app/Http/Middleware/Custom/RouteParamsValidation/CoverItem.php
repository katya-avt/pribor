<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use function abort;

class CoverItem
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
        $cover = $request->route('cover');
        $coverItem = $request->route('coverItem');

        $coverItemsArray = $cover->items->pluck('id')->toArray();

        if (in_array($coverItem->id, $coverItemsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
