<?php

namespace App\Services\ItemAvailabilityAndConsumption\OrderPoint;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

class Service
{
    public static function getInOrderPurchasedItemsReachedItsOrderPoint()
    {
        $inOrderPurchasedItems = self::getInOrderPurchasedItems();
        $inOrderPurchasedItemsRequiredQuantity = self::getInOrderPurchasedItemsRequiredQuantity();

        $inOrderPurchasedItemsReachedOrderPoint = collect();

        foreach($inOrderPurchasedItems->zip($inOrderPurchasedItemsRequiredQuantity) as $purchasedItemData) {
            if ($purchasedItemData[0]->cnt - $purchasedItemData[1]->cnt < $purchasedItemData[0]->purchasedItem->order_point) {
                $inOrderPurchasedItemsReachedOrderPoint->push($purchasedItemData);
            }
        }

        return $inOrderPurchasedItemsReachedOrderPoint;
    }

    public static function getOutOfOrderPurchasedItemsReachedItsOrderPoint()
    {
        $outOfOrderPurchasedItemsReachedOrderPointQuery = self::getOutOfOrderPurchasedItemsReachedItsOrderPointQuery();

        return $outOfOrderPurchasedItemsReachedOrderPointQuery->with('purchasedItem')->get();
    }

    public static function getReachedItsOrderPointItemsCount()
    {
        $outOfOrderPurchasedItemsReachedItsOrderPointCount = self::getOutOfOrderPurchasedItemsReachedItsOrderPointCount();

        $inOrderPurchasedItems = self::getInOrderPurchasedItems();
        $inOrderPurchasedItemsRequiredQuantity = self::getInOrderPurchasedItemsRequiredQuantity();

        $reachedItsOrderPointInOrderPurchasedItemsCount = 0;

        foreach($inOrderPurchasedItems->zip($inOrderPurchasedItemsRequiredQuantity) as $purchasedItemData) {
            if ($purchasedItemData[0]->cnt - $purchasedItemData[1]->cnt < $purchasedItemData[0]->purchasedItem->order_point) {
                $reachedItsOrderPointInOrderPurchasedItemsCount += 1;
            }
        }

        return $outOfOrderPurchasedItemsReachedItsOrderPointCount +
            $reachedItsOrderPointInOrderPurchasedItemsCount;
    }

    private static function getInOrderPurchasedItemsRequiredQuantity()
    {
        $inProductionOrderIds = self::getInProductionOrderIds();
        $purchasedItemIds = self::getPurchasedItemIds();

        $purchasedItemsCntFromOrderItemTable = DB::table('order_item')
            ->select(DB::raw('item_id, SUM(cnt) AS cnt'))
            ->whereIn('order_id', $inProductionOrderIds)
            ->whereIn('item_id', $purchasedItemIds)
            ->groupBy('item_id');

        $purchasedItemsCntFromOrderItemSpecificationTable = DB::table('order_item_specification')
            ->select(DB::raw('component_id AS item_id, SUM(component_cnt) AS cnt'))
            ->whereIn('order_id', $inProductionOrderIds)
            ->whereIn('component_id', $purchasedItemIds)
            ->groupBy('component_id');

        $unionTable = $purchasedItemsCntFromOrderItemTable
            ->unionAll($purchasedItemsCntFromOrderItemSpecificationTable);

        return DB::query()->select(DB::raw('item_id, SUM(cnt) AS cnt'))
            ->from(DB::raw("({$unionTable->toSql()}) as t"))
            ->mergeBindings($unionTable)->groupBy('item_id')->get()->sortBy('item_id');
    }

    private static function getOutOfOrderPurchasedItemsReachedItsOrderPointQuery()
    {
        $inOrderPurchasedItemIds = self::getInOrderPurchasedItemIds();

        return Item::whereHas('purchasedItem', function ($query) use ($inOrderPurchasedItemIds) {
            $query->whereNotIn('id', $inOrderPurchasedItemIds)->whereColumn('items.cnt', '<', 'purchased_items.order_point');
        });
    }

    private static function getOutOfOrderPurchasedItemsReachedItsOrderPointCount()
    {
        $outOfOrderPurchasedItemsReachedOrderPointQuery = self::getOutOfOrderPurchasedItemsReachedItsOrderPointQuery();

        return $outOfOrderPurchasedItemsReachedOrderPointQuery->count();
    }

    private static function getInOrderPurchasedItemIds()
    {
        $inOrderPurchasedItemsRequiredQuantity = self::getInOrderPurchasedItemsRequiredQuantity();

        return $inOrderPurchasedItemsRequiredQuantity->pluck('item_id');
    }

    private static function getInOrderPurchasedItems()
    {
        $inOrderPurchasedItemIds = self::getInOrderPurchasedItemIds();

        return Item::with('purchasedItem')->findMany($inOrderPurchasedItemIds);
    }

    private static function getPurchasedItemIds()
    {
        return Item::whereHas('purchasedItem')->pluck('id');
    }

    private static function getInProductionOrderIds()
    {
        return Order::where('status_id', OrderStatus::IN_PRODUCTION)->pluck('id');
    }
}
