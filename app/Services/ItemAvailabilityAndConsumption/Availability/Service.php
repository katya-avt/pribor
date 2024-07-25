<?php

namespace App\Services\ItemAvailabilityAndConsumption\Availability;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

class Service
{
    public function update(Item $item, $newCnt)
    {
        try {
            DB::beginTransaction();

            $item->update($newCnt);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }
}
