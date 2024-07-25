<?php

namespace App\Services\PeriodicRequisites\LaborPayment;

use App\Models\Range\Point;
use Illuminate\Support\Facades\DB;

class Service
{
    public function update(Point $point, $newBasePayment)
    {
        try {
            DB::beginTransaction();

            $point->update($newBasePayment);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }
}
