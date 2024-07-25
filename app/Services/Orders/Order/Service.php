<?php

namespace App\Services\Orders\Order;

use App\Models\Orders\Order;
use Illuminate\Support\Facades\DB;

class Service
{
    public function store($orderData)
    {
        try {
            DB::beginTransaction();

            Order::firstOrCreate($orderData);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function update(Order $order, $newOrderData)
    {
        try {
            DB::beginTransaction();

            $order->update($newOrderData);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }

    public function delete(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->items()->detach();

            DB::table('order_item_specification')
                ->where('order_id', $order->id)
                ->delete();

            DB::table('order_item_route')
                ->where('order_id', $order->id)
                ->delete();

            $order->delete();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_delete');
    }

    public function putIntoProduction(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->putIntoProduction();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_put_into_production');
    }

    public function completeProduction(Order $order)
    {
        try {
            DB::beginTransaction();

            $isOrderHasMissingItems = self::isOrderHasMissingItems($order);

            $isOrderComponentsHasMissingItems = self::isOrderComponentsHasMissingItems($order);

            if ($isOrderHasMissingItems || $isOrderComponentsHasMissingItems) {
                return __('messages.order_production_cannot_be_completed');
            }

            self::updateCntOfOrderItemsInStock($order);

            self::updateCntOfOrderComponentsInStock($order);

            $order->completeProduction();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_complete_production');
    }

    public function sendOnShipment(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->sendOnShipment();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_send_on_shipment');
    }

    public function ship(Order $order)
    {
        try {
            DB::beginTransaction();

            $order->ship();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_ship');
    }

    private function isOrderHasMissingItems(Order $order)
    {
        return $order->items()
            ->whereColumn('items.cnt', '<', 'order_item.cnt')
            ->exists();
    }

    private function isOrderComponentsHasMissingItems(Order $order)
    {
        return DB::select("WITH order_item_specification_cnt AS
            (SELECT component_id, SUM(component_cnt) AS cnt FROM order_item_specification
            WHERE order_id = $order->id GROUP BY component_id)
            SELECT EXISTS(SELECT order_item_specification_cnt.component_id,
            order_item_specification_cnt.cnt AS required_cnt, items.cnt AS current_cnt
            FROM order_item_specification_cnt INNER JOIN items ON order_item_specification_cnt.component_id = items.id
            WHERE items.cnt - order_item_specification_cnt.cnt < 0) AS isExist")[0]->isExist;
    }

    private function updateCntOfOrderItemsInStock(Order $order)
    {
        return DB::statement("UPDATE items,
    (SELECT order_id, item_id, cnt FROM order_item WHERE order_id = $order->id) AS orderItems
    SET items.cnt = items.cnt - orderItems.cnt
    WHERE items.id = orderItems.item_id");
    }

    private function updateCntOfOrderComponentsInStock(Order $order)
    {
        return DB::statement("UPDATE items,
    (SELECT component_id, SUM(component_cnt) AS cnt FROM order_item_specification
    WHERE order_id = $order->id GROUP BY component_id) AS orderItemSpecification
    SET items.cnt = items.cnt - orderItemSpecification.cnt
    WHERE items.id = orderItemSpecification.component_id");
    }
}
