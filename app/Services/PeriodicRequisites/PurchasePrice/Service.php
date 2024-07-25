<?php

namespace App\Services\PeriodicRequisites\PurchasePrice;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

class Service
{
    public function update(Item $item, $newPurchasePrice)
    {
        try {
            DB::beginTransaction();

            $item->purchasedItem()->update($newPurchasePrice);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }
}
