<?php

namespace App\Http\Controllers\Orders\ShippedOrderStatistics;

use App\Http\Controllers\Controller;
use App\Http\Filters\FilterConstants;
use App\Http\Filters\ShippedOrderStatisticsFilter;
use App\Http\Requests\Orders\ShippedOrderStatistics\IndexRequest;
use App\Services\Orders\ShippedOrderStatistics\Service;
use function app;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request, Service $service)
    {
        $data = $request->validated();

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $periods = FilterConstants::PERIODS;

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        return view('orders.shipped-order-statistics.index',
            compact('periods', 'totalProfitValue',
                'averageProfitabilityValue', 'ordersShippedOutOfTimeCount',
                'valueGroups', 'customerGroups', 'data'));
    }
}
