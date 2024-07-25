<?php

namespace App\Services\Range\PurchasedItems;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

class Service
{
    public function update(Item $item, $newData)
    {
        try {
            DB::beginTransaction();

            $item->purchasedItem()->update($newData);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }
}
