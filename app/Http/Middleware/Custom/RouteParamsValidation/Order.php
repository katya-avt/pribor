<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use App\Models\Orders\Order as OrderModel;
use function abort;

class Order
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

        $ordersArray = OrderModel::pluck('id')->toArray();

        if (in_array($order->id, $ordersArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
