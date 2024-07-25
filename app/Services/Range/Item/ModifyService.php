<?php

namespace App\Services\Range\Item;

use App\Models\Range\Detail;
use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\ItemType;
use App\Models\Range\PurchasedItem;
use Illuminate\Support\Facades\DB;

class ModifyService
{
    public function store($data)
    {
        try {
            DB::beginTransaction();

            $itemData = $data['item'];

            $item = Item::firstOrCreate($itemData);

            if ($itemData['group_id'] == Group::DETAIL) {
                $detailData = $data['detail'];
                $detailData['item_id'] = $item->id;

                Detail::firstOrCreate($detailData);
            }

            if ($data['item']['item_type_id'] == ItemType::PURCHASED) {
                $purchasedData = $data['purchased'];
                $purchasedData['item_id'] = $item->id;

                PurchasedItem::firstOrCreate($purchasedData);
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function update($item, $data)
    {
        try {
            DB::beginTransaction();

            $itemData = $data['item'];
            $item->update($itemData);

            $detailData = $data['detail'];
            $purchasedData = $data['purchased'];

            if ($item->detail && $itemData['group_id'] == Group::DETAIL) {
                $item->detail->update($detailData);
            }

            if (!$item->detail && $itemData['group_id'] == Group::DETAIL) {
                $detailData['item_id'] = $item->id;
                Detail::firstOrCreate($detailData);
            }

            if ($item->detail && $itemData['group_id'] != Group::DETAIL) {
                $item->detail->delete();
            }


            if ($item->purchasedItem && $itemData['item_type_id'] == ItemType::PURCHASED) {
                $item->purchasedItem->update($purchasedData);
            }

            if (!$item->purchasedItem && $itemData['item_type_id'] == ItemType::PURCHASED) {
                $purchasedData['item_id'] = $item->id;
                PurchasedItem::firstOrCreate($purchasedData);
            }

            if ($item->purchasedItem && $itemData['item_type_id'] != ItemType::PURCHASED) {
                $item->purchasedItem->delete();
            }

            if ($item->group->name != $itemData['group_id'] || $item->itemType->name != $itemData['item_type_id']) {
                $item->specifications()->detach();
                $item->covers()->detach();
                $item->routes()->detach();

                $item->update([
                    'specification_number' => null,
                    'cover_number' => null,
                    'route_number' => null,
                ]);
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }
}
