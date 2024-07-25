<?php

namespace App\Services\Orders\ShippedOrderStatistics;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use Illuminate\Support\Facades\DB;

class Service
{
    const TOTAL_PROFIT = 'totalProfit';
    const AVERAGE_PROFITABILITY = 'averageProfitability';
    const ORDERS_SHIPPED_OUT_OF_TIME_COUNT = 'ordersShippedOutOfTimeCount';
    const TOTAL_ORDERS_COUNT = 'totalOrdersCount';

    public function getTotalProfitValue()
    {
        $totalProfit = self::TOTAL_PROFIT;

        return Order::where('status_id', OrderStatus::SHIPPED)
            ->select(DB::raw("SUM(amount) - SUM(cost) AS $totalProfit"));
    }

    public function getAverageProfitabilityValue()
    {
        $averageProfitability = self::AVERAGE_PROFITABILITY;

        return Order::where('status_id', OrderStatus::SHIPPED)
            ->select(DB::raw("AVG((((CAST(amount AS DECIMAL(18,2)) - CAST(cost AS DECIMAL(18,2))) / CAST(amount AS DECIMAL(18,2)))) * 100) AS $averageProfitability"));
    }

    public function getOrdersShippedOutOfTimeCount()
    {
        $ordersShippedOutOfTimeCount = self::ORDERS_SHIPPED_OUT_OF_TIME_COUNT;

        return Order::where('status_id', OrderStatus::SHIPPED)
            ->select(DB::raw("COUNT(id) AS $ordersShippedOutOfTimeCount"))
            ->whereColumn('completion_date', '>', 'closing_date');
    }

    public function getCustomerDistribution(int $filteredTotalOrdersCount)
    {
        return Order::join('customers', 'orders.customer_inn', '=', 'customers.inn')
            ->where('orders.status_id', OrderStatus::SHIPPED)
            ->select(DB::raw("customers.name,
            (COUNT(orders.customer_inn) / $filteredTotalOrdersCount * 100) as distribution"))
            ->groupBy('orders.customer_inn', 'customers.name');
    }

    public function getTotalOrdersCount()
    {
        return Order::where('status_id', OrderStatus::SHIPPED)
            ->select(DB::raw('COUNT(id) as totalOrdersCount'));
    }
}
