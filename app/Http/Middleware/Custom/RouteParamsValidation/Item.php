<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use App\Models\Range\Item as ItemModel;
use function abort;

class Item
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
        $item = $request->route('item');

        $itemsArray = ItemModel::pluck('id')->toArray();

        if (in_array($item->id, $itemsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
