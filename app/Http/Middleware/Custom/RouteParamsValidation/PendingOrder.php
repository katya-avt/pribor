<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use Closure;
use Illuminate\Http\Request;
use function abort;

class PendingOrder
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
        $pendingOrders = Order::where('status_id', OrderStatus::PENDING);

        if ($pendingOrders->doesntExist()) {
            return abort(404);
        }
        $pendingOrdersArray = $pendingOrders->pluck('id')->toArray();

        $order = $request->route('order');

        if (in_array($order->id, $pendingOrdersArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
