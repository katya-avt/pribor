<?php

namespace App\Services\Admin\MarkedForDeletion\Item;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

class Service
{
    public function restore($item): string
    {
        try {
            DB::beginTransaction();

            $deletedItem = Item::onlyTrashed()->find($item);
            $deletedItem->restore();

            if ($deletedItem->group->isCover()) {
                DB::statement("UPDATE covers INNER JOIN cover_item
ON covers.number = cover_item.cover_number
SET valid_to = NULL
WHERE cover_item.item_id = $item
AND covers.valid_to IS NOT NULL");
            } else {
                DB::statement("UPDATE specifications INNER JOIN specification_item
ON specifications.number = specification_item.specification_number
SET valid_to = NULL
WHERE specification_item.item_id = $item
AND specifications.valid_to IS NOT NULL");
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_restore');
    }
}
