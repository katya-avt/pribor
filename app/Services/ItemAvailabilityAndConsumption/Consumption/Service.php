<?php

namespace App\Services\ItemAvailabilityAndConsumption\Consumption;

use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Orders\OrderItemRoute;
use App\Models\Orders\OrderItemSpecification;
use App\Models\Orders\OrderStatus;
use Illuminate\Support\Facades\DB;

class Service
{
    public static function getCntByOrderIdAndItemId(string $orderId, string $itemId)
    {
        return OrderItem::where('order_id', $orderId)
            ->where('item_id', $itemId)->first()->cnt;
    }

    public static function getOrderItemTotalRequiredCnt(string $orderId, string $itemId)
    {
        $tableWithRowNumberQuery = self::getTableWithRowNumberQuery(OrderItem::class);
        $tableQuery = "({$tableWithRowNumberQuery->toSql()}) as t";

        DB::statement("SET @row_number = 0;");

        $currentOrderItemRowNumber = DB::query()->from(DB::raw($tableQuery))
            ->mergeBindings($tableWithRowNumberQuery->getQuery())
            ->where('order_id', $orderId)
            ->where('item_id', $itemId)
            ->value('row_number');

        DB::statement("SET @row_number = 0;");

        return DB::query()->from(DB::raw($tableQuery))
            ->mergeBindings($tableWithRowNumberQuery->getQuery())
            ->where('row_number', '<=', $currentOrderItemRowNumber)
            ->where('item_id', $itemId)->sum('cnt');
    }

    public static function getOrderItemComponentTotalRequiredCnt(string $componentId, string $currentComponentIdPrimaryKey)
    {
        $tableWithRowNumberQuery = self::getTableWithRowNumberQuery(OrderItemSpecification::class);
        $tableQuery = "({$tableWithRowNumberQuery->toSql()}) as t";

        DB::statement("SET @row_number = 0;");

        $currentOrderItemComponentRowNumber = DB::query()->from(DB::raw($tableQuery))
            ->mergeBindings($tableWithRowNumberQuery->getQuery())
            ->where('order_item_specification_id', $currentComponentIdPrimaryKey)
            ->value('row_number');

        DB::statement("SET @row_number = 0;");

        return DB::query()->from(DB::raw($tableQuery))
            ->mergeBindings($tableWithRowNumberQuery->getQuery())
            ->where('row_number', '<=', $currentOrderItemComponentRowNumber)
            ->where('component_id', $componentId)->sum('component_cnt');
    }

    public static function getSortedInProductionOrdersQuery()
    {
        $sortedOrderIdsString = self::getSortedOrderIdsString();

        return Order::with('customer')
            ->where('status_id', OrderStatus::IN_PRODUCTION)
            ->orderByRaw("FIELD(id, $sortedOrderIdsString)");
    }

    private static function getInProductionOrderIdsArray()
    {
        return Order::where('status_id', OrderStatus::IN_PRODUCTION)->pluck('id')->toArray();
    }

    private static function getSortedOrderIdsString()
    {
        $inProductionOrderIdsArray = self::getInProductionOrderIdsArray();

        $orderItemRouteTableQuery = OrderItemRoute::select(DB::raw("order_id, CEILING(SUM((unit_time + working_time + lead_time) * cnt) / 60 / 24) as time_in_days"))
            ->whereIn('order_id', $inProductionOrderIdsArray)
            ->groupBy('order_id');

        return Order::joinSub($orderItemRouteTableQuery, 'orderItemRouteTableQuery', function ($join) {
            $join->on('orders.id', '=', 'orderItemRouteTableQuery.order_id');
        })->select(DB::raw("orders.id, DATEDIFF(orders.closing_date,
        DATE_ADD(orders.launch_date, INTERVAL orderItemRouteTableQuery.time_in_days DAY)) as diff"))
            ->orderBy('diff')->pluck('id')->implode(', ');
    }

    private static function getTableWithRowNumberQuery($model)
    {
        $inProductionOrderIdsArray = self::getInProductionOrderIdsArray();
        $sortedOrderIdsString = self::getSortedOrderIdsString();

        return $model::select(DB::raw("*, @row_number := @row_number + 1 AS `row_number`"))
            ->whereIn('order_id', $inProductionOrderIdsArray)->orderByRaw("FIELD(order_id, $sortedOrderIdsString)");
    }
}
