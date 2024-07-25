<?php

namespace App\Http\Controllers\Orders\Order;

use App\Http\Controllers\Controller;
use App\Http\Filters\OrderFilter;
use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Http\Requests\Orders\Order\IndexRequest;

class IndexController extends Controller
{
    public function __invoke($orderStatus, IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        $statusId = OrderStatus::where('url_param_name', $orderStatus)->first()->id;

        $orders = Order::with('customer')
            ->where('status_id', $statusId)
            ->filter($filter)
            ->paginate(10);

        $columnsAndSortNameMatching = Order::getColumnsAndSortNameMatching();
        $sortDirectionAndSortNameMatching = Order::getSortDirectionAndSortNameMatching();

        return view('orders.orders.index',
            compact('orders', 'orderStatus',
                'columnsAndSortNameMatching', 'sortDirectionAndSortNameMatching', 'data'));
    }
}
