<?php

namespace App\Services\Range\Item\CurrentSpecificationsAndRoute;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

class Service
{
    public function update(Item $item, $newCurrentSpecificationsAndRoute)
    {
        try {
            DB::beginTransaction();

            $item->update($newCurrentSpecificationsAndRoute);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }
}
