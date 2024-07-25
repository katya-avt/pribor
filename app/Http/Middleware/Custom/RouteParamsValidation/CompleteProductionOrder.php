<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use Closure;
use Illuminate\Http\Request;
use function abort;

class CompleteProductionOrder
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
        $completeProductionOrders = Order::where('status_id', OrderStatus::PRODUCTION_COMPLETED);

        if ($completeProductionOrders->doesntExist()) {
            return abort(404);
        }
        $completeProductionOrdersArray = $completeProductionOrders->pluck('id')->toArray();

        $order = $request->route('order');

        if (in_array($order->id, $completeProductionOrdersArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
