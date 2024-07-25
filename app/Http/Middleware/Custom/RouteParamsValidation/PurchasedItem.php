<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use App\Models\Range\Item;
use function abort;

class PurchasedItem
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
        $purchasedItem = $request->route('item');

        $purchasedItemsArray = Item::has('purchasedItem')->pluck('id')->toArray();

        if (in_array($purchasedItem->id, $purchasedItemsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
