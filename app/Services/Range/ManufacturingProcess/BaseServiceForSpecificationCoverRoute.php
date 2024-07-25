<?php

namespace App\Services\Range\ManufacturingProcess;

use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;
use function app;

abstract class BaseServiceForSpecificationCoverRoute
{
    protected $model;
    protected string $modelName;
    protected string $fullModelName;
    protected string $relationMethodName;
    protected string $columnName;

    public function __construct()
    {
        $this->fullModelName = $this->getFullModelName();
        $this->modelName = lcfirst(class_basename($this->fullModelName));
        $this->model = app($this->fullModelName);
        $this->relationMethodName = $this->getRelationMethodName();
        $this->columnName = $this->modelName . '_number';
    }

    public function store($number)
    {
        try {
            DB::beginTransaction();

            $model = $this->model;

            $model::firstOrCreate($number);

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_store');
    }

    public function update($model, $newNumber): string
    {
        try {
            DB::beginTransaction();

            if ($model instanceof $this->fullModelName) {
                if ($model->number != $newNumber['number']) {
                    $columnName = $this->columnName;
                    $relationMethodName = $this->relationMethodName;

                    $this->store($newNumber);
                    Item::where($columnName, $model->number)->update([$columnName => $newNumber['number']]);
                    $model->relatedItems()->update(["item_{$this->modelName}" . '.' . $columnName => $newNumber['number']]);
                    $model->$relationMethodName()->update([$this->modelName . "_" . rtrim($relationMethodName, 's') . '.' . $columnName => $newNumber['number']]);
                    $model->delete($model->number);
                }
            } else {
                return __('messages.failed_passing_data_type');
            }

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_update');
    }

    public function delete($model): string
    {
        try {
            DB::beginTransaction();

            if ($model instanceof $this->fullModelName) {
                $relationMethodName = $this->relationMethodName;
                $columnName = $this->columnName;

                Item::where($columnName, $model->number)->update([$columnName => null]);
                $model->relatedItems()->detach();
                $model->$relationMethodName()->detach();
                $model->delete();
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
