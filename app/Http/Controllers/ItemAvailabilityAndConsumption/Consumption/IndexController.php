<?php

namespace App\Http\Controllers\ItemAvailabilityAndConsumption\Consumption;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Http\Requests\ItemAvailabilityAndConsumption\Consumption\IndexRequest;
use App\Services\ItemAvailabilityAndConsumption\Consumption\Service;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request, Service $service)
    {
        $data = $request->validated();
        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        $orders = $service::getSortedInProductionOrdersQuery()
            ->filter($filter)
            ->paginate(10);

        return view('item-availability-and-consumption.consumption.index',
            compact('orders','data'));
    }
}
