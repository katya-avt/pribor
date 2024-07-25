<?php

namespace App\Services\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

class ModifyService
{
    public function store(Order $order, $newOrderItemData)
    {
        try {
            DB::beginTransaction();

            $orderItemData = array_diff_key($newOrderItemData, ['item_id' => null]);

            $item = Item::getItemByDrawing($newOrderItemData['item_id']);

            $order->items()->attach($item->id, $orderItemData);

            $cnt = $newOrderItemData['cnt'];

            if ($item->specification_number || $item->cover_number || $item->route_number) {
                if ($item->specification_number || $item->cover_number) {
                    self::addToDbOrderItemSpecification($order->id, $item->id, $cnt);
                }
                if ($item->route_number) {
                    self::addToDbOrderItemRoute($order->id, $item->id, $cnt);
                }

                $orderItemManufacturingCost = self::getOrderItemManufacturingCost($order->id, $item->id);

                if ($item->itemType->isPurchased()) {
                    $orderItemPurchasedPrice = $item->purchasedItem->purchase_price;

                    $order->items()->where('item_id', $item->id)
                        ->update(['order_item.cost' => DB::raw("order_item.cnt * $orderItemPurchasedPrice + $orderItemManufacturingCost")]);
                } else {
                    $order->items()->where('item_id', $item->id)
                        ->update(['order_item.cost' => $orderItemManufacturingCost]);
                }

            } else {
                if ($item->itemType->isPurchased()) {
                    $orderItemPurchasedPrice = $item->purchasedItem->purchase_price;

                    $order->items()->where('item_id', $item->id)
                        ->update(['order_item.cost' => DB::raw("order_item.cnt * $orderItemPurchasedPrice")]);
                }
            }

            $order->items()->where('item_id', $item->id)
                ->update(['order_item.amount' => DB::raw('order_item.per_unit_price * order_item.cnt')]);

            self::calculateOrderAmountAndCost($order);

            self::disallowOrderComponentsModification($order, $item);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function update(Order $order, Item $orderItem, $newOrderItemData)
    {
        try {
            DB::beginTransaction();

            self::delete($order, $orderItem);
            self::store($order, $newOrderItemData);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }

    public function delete(Order $order, Item $orderItem)
    {
        try {
            DB::beginTransaction();

            DB::table('order_item')
                ->where('order_id', $order->id)
                ->where('item_id', $orderItem->id)
                ->delete();

            DB::table('order_item_specification')
                ->where('order_id', $order->id)
                ->where('item_id', $orderItem->id)
                ->delete();

            DB::table('order_item_route')
                ->where('order_id', $order->id)
                ->where('item_id', $orderItem->id)
                ->delete();

            self::calculateOrderAmountAndCost($order);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_delete');
    }

    private function calculateOrderAmountAndCost(Order $order)
    {
        $orderAmount = $order->items()->sum('amount');
        $orderCost = $order->items()->sum('cost');

        $order->update(['amount' => $orderAmount, 'cost' => $orderCost]);
    }

    private function addToDbOrderItemSpecification(string $orderId, string $orderItemId, string $orderItemCnt)
    {
        $orderItemSpecification =
            "INSERT INTO order_item_specification (order_id, item_id, current_item_id, current_item_parent_id,
                                      current_specification_number, current_cover_number, current_number,
                                      component_id, component_cnt, component_purchase_price,
                                      total_component_purchase_price)
WITH RECURSIVE manufacturing_data AS (
SELECT specification_cover_union.manufacturing_number, specification_cover_union.item_id,
specification_cover_union.cnt, purchased_items.purchase_price FROM
(
SELECT specification_item.specification_number AS manufacturing_number, specification_item.item_id, specification_item.cnt
FROM specification_item
UNION ALL
SELECT cover_item.cover_number, cover_item.item_id, cover_item.area * cover_item.consumption AS cnt FROM cover_item
) AS specification_cover_union
LEFT JOIN purchased_items ON specification_cover_union.item_id = purchased_items.item_id),
order_item_specification AS (
  SELECT
    $orderId AS order_id,
    $orderItemId AS item_id,
    i.id AS current_item_id,
    CASE
      WHEN i.id = $orderItemId THEN NULL
      ELSE i.id
    END AS current_item_parent_id,
    i.specification_number AS current_specification_number,
    i.cover_number AS current_cover_number,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.manufacturing_number
      ELSE NULL
    END AS current_number,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.cnt * $orderItemCnt
      ELSE NULL
    END AS component_cnt,
    manufacturing_data.purchase_price AS component_purchase_price,
    CASE
      WHEN manufacturing_data.purchase_price IS NULL
      THEN manufacturing_data.cnt * $orderItemCnt
      ELSE manufacturing_data.cnt * $orderItemCnt * manufacturing_data.purchase_price
    END AS total_component_purchase_price
  FROM
    items AS i LEFT JOIN manufacturing_data ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE
    i.id = $orderItemId

  UNION ALL

  SELECT
    $orderId AS order_id,
    $orderItemId AS item_id,
    i.id AS current_item_id,
    ois.current_item_id AS current_item_parent_id,
    i.specification_number AS current_specification_number,
    i.cover_number AS current_cover_number,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.manufacturing_number
      ELSE NULL
    END AS current_number,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL THEN manufacturing_data.item_id
      ELSE NULL
    END AS component_id,
    CASE
      WHEN i.specification_number IS NOT NULL OR i.cover_number IS NOT NULL
      THEN manufacturing_data.cnt * ois.component_cnt
      ELSE NULL
    END AS component_cnt,
    manufacturing_data.purchase_price AS component_purchase_price,
    CASE
      WHEN manufacturing_data.purchase_price IS NULL
      THEN manufacturing_data.cnt * ois.component_cnt
      ELSE manufacturing_data.cnt * ois.component_cnt * manufacturing_data.purchase_price
    END AS total_component_purchase_price
  FROM
    order_item_specification ois,
    items AS i LEFT JOIN manufacturing_data ON (i.specification_number = manufacturing_data.manufacturing_number OR i.cover_number = manufacturing_data.manufacturing_number)
  WHERE i.id = ois.component_id
)
SELECT order_id, item_id, current_item_id, current_item_parent_id,
       current_specification_number, current_cover_number, current_number, component_id,
       component_cnt, component_purchase_price, total_component_purchase_price
       FROM order_item_specification
       WHERE component_id IS NOT NULL;";

        DB::statement($orderItemSpecification);
    }

    private function addToDbOrderItemRoute(string $orderId, string $orderItemId, string $orderItemCnt)
    {
        $orderItemRoute =
            "INSERT INTO order_item_route (order_id, item_id, current_item_id, cnt,
                              route_number, point_number, point_code, operation_code,
                              rate_code, unit_time, working_time, lead_time, base_payment)
WITH route_data AS (
SELECT items.id, route_point.route_number, route_point.point_number, route_point.point_code, route_point.operation_code, route_point.rate_code,
route_point.unit_time, route_point.working_time, route_point.lead_time, points.base_payment
FROM items
LEFT JOIN route_point ON items.route_number = route_point.route_number
LEFT JOIN points ON route_point.point_code = points.code
),
item_total_cnt AS (
SELECT item_id AS current_item_id, cnt
FROM order_item WHERE order_id = $orderId AND item_id = $orderItemId
UNION ALL
SELECT component_id AS current_item_id, SUM(component_cnt) as cnt
FROM order_item_specification
WHERE order_id = $orderId AND item_id = $orderItemId
GROUP BY component_id
    )
SELECT $orderId AS order_id, $orderItemId AS item_id, item_total_cnt.current_item_id, item_total_cnt.cnt, route_data.route_number,
       route_data.point_number, route_data.point_code, route_data.operation_code,
       route_data.rate_code, route_data.unit_time, route_data.working_time,
       route_data.lead_time, route_data.base_payment
FROM item_total_cnt INNER JOIN route_data
ON item_total_cnt.current_item_id = route_data.id
WHERE route_data.route_number IS NOT NULL;";

        DB::statement($orderItemRoute);
    }

    private function getOrderItemManufacturingCost(string $orderId, string $orderItemId)
    {
        return DB::select("SELECT SUM(total) AS total FROM (
SELECT SUM(total_component_purchase_price) AS total FROM order_item_specification
WHERE order_id = $orderId AND item_id = $orderItemId AND component_purchase_price IS NOT NULL
UNION ALL
SELECT SUM(order_item_route.cnt * rates.factor * order_item_route.base_payment * (order_item_route.unit_time + order_item_route.working_time + order_item_route.lead_time)) AS total
FROM order_item_route INNER JOIN rates
ON order_item_route.rate_code = rates.code
WHERE order_id = $orderId AND item_id = $orderItemId
    ) t")[0]->total;
    }

    private function disallowOrderComponentsModification(Order $order, $item)
    {
        self::disallowOrderItemsModification($order, $item);
        self::disallowOrderItemSpecificationsModification($order, $item);
        self::disallowOrderItemCoversModification($order, $item);
        self::disallowOrderItemRoutesModification($order, $item);
    }

    private function disallowOrderItemsModification(Order $order, $item)
    {
        DB::table('items')->where('id', $item->id)
            ->update(['added_to_order_at' => now()]);

        DB::table('items')
            ->whereIn('id', function ($query) use ($order, $item) {
                $query->select('order_item_specification.component_id')
                    ->from('order_item_specification')
                    ->where('order_item_specification.order_id', $order->id)
                    ->where('order_item_specification.item_id', $item->id)
                    ->distinct();
            })->update(['added_to_order_at' => now()]);
    }

    private function disallowOrderItemSpecificationsModification(Order $order, $item)
    {
        DB::table('specifications')
            ->where('number', function ($query) use ($item) {
                $query->select('items.specification_number')
                    ->from('items')
                    ->where('items.id', $item->id);
            })->update(['added_to_order_at' => now()]);

        DB::table('specifications')
            ->whereIn('number', function ($query) use ($order, $item) {
                $query->select('order_item_specification.current_specification_number')
                    ->from('order_item_specification')
                    ->where('order_item_specification.order_id', $order->id)
                    ->where('order_item_specification.item_id', $item->id)
                    ->distinct();
            })->update(['added_to_order_at' => now()]);
    }

    private function disallowOrderItemCoversModification(Order $order, $item)
    {
        DB::table('covers')->where('number', function ($query) use ($item) {
            $query->select('items.cover_number')
                ->from('items')
                ->where('items.id', $item->id);
        })->update(['added_to_order_at' => now()]);

        DB::table('covers')
            ->whereIn('number', function ($query) use ($order, $item) {
                $query->select('order_item_specification.current_cover_number')
                    ->from('order_item_specification')
                    ->where('order_item_specification.order_id', $order->id)
                    ->where('order_item_specification.item_id', $item->id)
                    ->distinct();
            })->update(['added_to_order_at' => now()]);
    }

    private function disallowOrderItemRoutesModification(Order $order, $item)
    {
        DB::table('routes')->where('number', function ($query) use ($item) {
            $query->select('items.route_number')
                ->from('items')
                ->where('items.id', $item->id);
        })->update(['added_to_order_at' => now()]);

        DB::table('routes')
            ->whereIn('number', function ($query) use ($order, $item) {
                $query->select('order_item_route.route_number')
                    ->from('order_item_route')
                    ->where('order_item_route.order_id', $order->id)
                    ->where('order_item_route.item_id', $item->id)->distinct();
            })->update(['added_to_order_at' => now()]);
    }
}
