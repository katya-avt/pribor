<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use Closure;
use Illuminate\Http\Request;
use function abort;

class InProductionOrder
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
        $inProductionOrders = Order::where('status_id', OrderStatus::IN_PRODUCTION);

        if ($inProductionOrders->doesntExist()) {
            return abort(404);
        }
        $inProductionOrdersArray = $inProductionOrders->pluck('id')->toArray();

        $order = $request->route('order');

        if (in_array($order->id, $inProductionOrdersArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
