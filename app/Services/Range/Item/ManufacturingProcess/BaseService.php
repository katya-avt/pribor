<?php

namespace App\Services\Range\Item\ManufacturingProcess;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;

abstract class BaseService
{
    protected string $fullModelName;
    protected string $relationMethodName;

    public function __construct()
    {
        $this->fullModelName = $this->getFullModelName();
        $this->relationMethodName = $this->getRelationMethodName();
    }

    public function store(Item $item, string $number)
    {
        try {
            DB::beginTransaction();

            $relationMethodName = $this->relationMethodName;

            $item->$relationMethodName()->attach($number);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function delete(Item $item, $model): string
    {
        try {
            DB::beginTransaction();

            if ($model instanceof $this->fullModelName) {
                $model->relatedItems()->detach($item->id);
            } else {
                return __('messages.failed_passing_data_type');
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_delete');
    }

    abstract protected function getFullModelName();

    abstract protected function getRelationMethodName();
}
