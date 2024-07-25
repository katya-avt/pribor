<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use function abort;

class OrderItem
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
        $order = $request->route('order');
        $orderItem = $request->route('orderItem');

        $orderItemsArray = $order->items->pluck('id')->toArray();

        if (in_array($orderItem->id, $orderItemsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
