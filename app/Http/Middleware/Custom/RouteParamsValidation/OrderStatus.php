<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\OrderStatus as OrderStatusModel;
use App\Models\Users\Permission;
use Closure;
use Illuminate\Http\Request;
use function abort;
use function auth;

class OrderStatus
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
        if (auth()->check()) {
            $orderStatusFromRoute = $request->route('orderStatus');
            $allOrderStatusesArray = OrderStatusModel::pluck('url_param_name')->toArray();

            if (!in_array($orderStatusFromRoute, $allOrderStatusesArray)) {
                return abort(404, 'NOT FOUND.');
            }

            $permissionsAndOrderStatusesMatching = [
                Permission::PENDING_ORDERS_VIEW => OrderStatusModel::PENDING_URL_PARAM_NAME,
                Permission::IN_PRODUCTION_ORDERS_VIEW => OrderStatusModel::IN_PRODUCTION_URL_PARAM_NAME,
                Permission::PRODUCTION_COMPLETED_ORDERS_VIEW => OrderStatusModel::PRODUCTION_COMPLETED_URL_PARAM_NAME,
                Permission::ON_SHIPMENT_ORDERS_VIEW => OrderStatusModel::ON_SHIPMENT_URL_PARAM_NAME,
                Permission::SHIPPED_ORDERS_VIEW => OrderStatusModel::SHIPPED_URL_PARAM_NAME,
            ];

            $authorizedOrderStatuses = [];

            foreach ($permissionsAndOrderStatusesMatching as $permission => $orderStatus) {
                if (auth()->user()->can($permission)) {
                    $authorizedOrderStatuses[] = $orderStatus;
                }
            }

            if (in_array($orderStatusFromRoute, $authorizedOrderStatuses)) {
                return $next($request);
            }
        }
        return abort(403, 'THIS ACTION IS UNAUTHORIZED.');
    }
}
