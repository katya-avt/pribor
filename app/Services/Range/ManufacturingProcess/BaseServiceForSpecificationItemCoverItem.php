<?php

namespace App\Services\Range\ManufacturingProcess;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;
use function app;

abstract class BaseServiceForSpecificationItemCoverItem
{
    protected $model;
    protected string $modelName;
    protected string $fullModelName;

    public function __construct()
    {
        $this->fullModelName = $this->getFullModelName();
        $this->modelName = lcfirst(class_basename($this->fullModelName));
        $this->model = app($this->fullModelName);
    }

    public function store($model, $specificationItemData): string
    {
        try {
            DB::beginTransaction();

            $newSpecificationItem = Item::getItemByDrawing($specificationItemData['drawing']);

            $specificationItemData = array_diff_key($specificationItemData, ['drawing' => null]);

            $model->items()->attach($newSpecificationItem->id, $specificationItemData);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function update($model, Item $specificationItem, $newSpecificationItemData)
    {
        try {
            DB::beginTransaction();

            $modelName = $this->modelName;

            $newSpecificationItem = Item::getItemByDrawing($newSpecificationItemData['drawing']);
            $newSpecificationItemData = array_diff_key($newSpecificationItemData, ['drawing' => null]);
            $data = ['item_id' => $newSpecificationItem->id] + $newSpecificationItemData;

            DB::table($modelName . '_item')
                ->where($modelName . '_number', $model->number)
                ->where('item_id', $specificationItem->id)
                ->update($data);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }

    public function delete($model, Item $specificationItem)
    {
        try {
            DB::beginTransaction();

            $modelName = $this->modelName;

            DB::table($modelName . '_item')
                ->where($modelName . '_number', $model->number)
                ->where('item_id', $specificationItem->id)
                ->delete();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_delete');
    }

    abstract protected function getFullModelName();
}
