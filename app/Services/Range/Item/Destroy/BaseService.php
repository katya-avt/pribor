<?php

namespace App\Services\Range\Item\Destroy;

use App\Models\Range\Cover;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use Illuminate\Support\Facades\DB;
use function app;
use function class_basename;

abstract class BaseService
{
    const PREVIOUS = 'previous_';

    protected $model;
    protected $modelName;
    protected $relatedMethodName;
    protected $consumptionAttributeName;
    protected $additionalAttributeNames;

    public function __construct()
    {
        $fullModelName = $this->getFullModelName();
        $this->model = app($fullModelName);
        $this->modelName = class_basename($fullModelName);
        $this->relatedMethodName = 'related' . $this->modelName . 's';

        $this->consumptionAttributeName = $this->model::getConsumptionAttributeName();
        $this->namesOfAdditionalAttributesRequireSummation = $this->model::getNamesOfAdditionalAttributesRequireSummation();
        $this->namesOfAdditionalAttributesNotRequireSummation = $this->model::getNamesOfAdditionalAttributesNotRequireSummation();
    }

    public function store(Item $item, $replacementData)
    {
        try {
            DB::beginTransaction();

            $relatedMethodName = $this->relatedMethodName;

            $model = $this->getModel();
            $primaryKey = $model->getKeyName();

            $consumptionAttributeName = $this->consumptionAttributeName;
            $consumptionAttributeNameWithPrevious = self::PREVIOUS . $consumptionAttributeName;

            $replacementItem = Item::getItemByDrawing($replacementData['drawing']);
            $relatedSpecificationsNumbers = $item->{$relatedMethodName}->pluck($primaryKey);
            $relatedSpecifications = $model::findMany($relatedSpecificationsNumbers);


            $relatedSpecifications->map(function ($relatedSpecification) use ($item, $replacementItem, $replacementData, $consumptionAttributeName, $consumptionAttributeNameWithPrevious) {

                $replacedSpecificationItems = self::getReplacementSpecificationItems($relatedSpecification, $item, $replacementItem);

                $replacedSpecificationItemsGroupById = $replacedSpecificationItems->groupBy('id');

                $newSpecificationData = $replacedSpecificationItemsGroupById->map(function ($modelCollection, $id) use ($replacementData, $relatedSpecification, $consumptionAttributeName, $consumptionAttributeNameWithPrevious) {
                    $requireSummationAttributesArray = [];
                    $notRequireSummationAttributesArray = [];

                    if (self::isModelHasAdditionalAttributesRequireSummation()) {
                        $requireSummationAttributesArray = self::getRequireSummationAttributesArray($modelCollection);
                    }

                    if (self::isModelHasAdditionalAttributesNotRequireSummation()) {
                        $notRequireSummationAttributesArray = self::getNotRequireSummationAttributesArray($modelCollection);
                    }

                    $generalData = [
                        'item_id' => $id
                    ];

                    $optionalData = $requireSummationAttributesArray + $notRequireSummationAttributesArray;

                    if ($modelCollection->count() > 1) {
                        $totalCnt = self::getTotalConsumption($modelCollection, $replacementData);

                        $generalData[$consumptionAttributeName] = $totalCnt;

                        return $generalData + $optionalData;

                    } else {
                        $generalData[$consumptionAttributeName] = $modelCollection[0]->pivot ?
                            $modelCollection[0]->pivot->{$consumptionAttributeName} :
                            $replacementData['factor'] * $modelCollection[0]->{$consumptionAttributeNameWithPrevious};

                        return $generalData + $optionalData;
                    }
                });

                $newSpecificationVersion = self::getNewSpecificationVersion($relatedSpecification);

                self::saveNewSpecificationToDb($relatedSpecification, $newSpecificationVersion, $newSpecificationData);

                $relatedSpecification->update(['valid_to' => date('Y-m-d')]);
            });

            $item->delete();

            DB::commit();

        } catch (\Exception $exception) {
            DB::rollBack();
            return $exception->getMessage();
        }

        return __('messages.successful_delete');
    }

    abstract protected function getFullModelName();

    protected function getModel()
    {
        return $this->model;
    }

    private function getReplacementSpecificationItems(Specification|Cover $relatedSpecification, Item $item, $replacementItem)
    {
        $model = $this->getModel();
        $primaryKey = $model->getKeyName();

        $consumptionAttributeName = $this->consumptionAttributeName;

        $additionalAttributeNames = $this->namesOfAdditionalAttributesRequireSummation +
            $this->namesOfAdditionalAttributesNotRequireSummation;

        $relatedSpecificationItems = $relatedSpecification->items;
        return $relatedSpecificationItems->map(function ($currentItem) use ($item, $replacementItem, $primaryKey, $consumptionAttributeName, $additionalAttributeNames) {
            if ($currentItem->is($item)) {
                $previousConsumption = $currentItem->pivot->{$consumptionAttributeName};
                $previousSpecificationNumber = $currentItem->pivot->{$primaryKey};

                $replacementItem->setAttribute(self::PREVIOUS . $consumptionAttributeName, $previousConsumption);
                $replacementItem->setAttribute(self::PREVIOUS . $primaryKey, $previousSpecificationNumber);

                if (self::isModelHasAdditionalAttributes()) {
                    foreach ($additionalAttributeNames as $additionalAttributeName) {
                        $previousAdditionalAttributeValue = $currentItem->pivot->{$additionalAttributeName};
                        $replacementItem->setAttribute(self::PREVIOUS . $additionalAttributeName, $previousAdditionalAttributeValue);
                    }
                }
                return $replacementItem;
            } else {
                return $currentItem;
            }
        });
    }

    private function getTotalConsumption($modelCollection, $replacementData)
    {
        $consumptionAttributeName = $this->consumptionAttributeName;

        return $modelCollection->sum(function ($model) use ($replacementData, $consumptionAttributeName) {
            return $model->pivot ? $model->pivot->{$consumptionAttributeName} : $replacementData['factor'] * $model->{self::PREVIOUS . $consumptionAttributeName};
        });
    }

    private function saveNewSpecificationToDb($relatedSpecification, $newSpecificationVersion, $newSpecificationData)
    {
        $model = $this->getModel();
        $primaryKey = $model->getKeyName();

        $newSpecificationNumber = $newSpecificationVersion == 1 ?
            $relatedSpecification->{$primaryKey} . '-' . $newSpecificationVersion :
            preg_replace("/-(\d+)/", '-' . $newSpecificationVersion, $relatedSpecification->{$primaryKey});

        $newSpecification = $model::firstOrCreate([$primaryKey => $newSpecificationNumber]);

        $relatedOldSpecificationItemIds = $relatedSpecification->relatedItems->pluck('id');
        $newSpecification->relatedItems()->attach($relatedOldSpecificationItemIds);

        $newSpecificationData->each(function ($specificationRow) use ($newSpecification, $newSpecificationData) {
            $newSpecification->items()->attach($specificationRow['item_id'],
                array_diff_key($specificationRow, ['item_id' => null]));
        });
    }

    private function getNewSpecificationVersion($relatedSpecification)
    {
        $model = $this->getModel();
        $primaryKey = $model->getKeyName();

        $latestSpecificationVersion = str_contains($relatedSpecification->{$primaryKey}, '-') ?
            (int)explode('-', $relatedSpecification->{$primaryKey})[1] : 0;

        return $latestSpecificationVersion + 1;
    }

    private function isModelHasAdditionalAttributesRequireSummation()
    {
        return !empty($this->namesOfAdditionalAttributesRequireSummation);
    }

    private function isModelHasAdditionalAttributesNotRequireSummation()
    {
        return !empty($this->namesOfAdditionalAttributesNotRequireSummation);
    }

    private function isModelHasAdditionalAttributes()
    {
        return self::isModelHasAdditionalAttributesRequireSummation() ||
            self::isModelHasAdditionalAttributesNotRequireSummation();
    }

    private function getTotal($modelCollection, $attributeName)
    {
        return $modelCollection->sum(function ($model) use ($attributeName) {
            return $model->pivot ? $model->pivot->{$attributeName} : $model->{self::PREVIOUS . $attributeName};
        });
    }

    private function getRequireSummationAttributesArray($modelCollection)
    {
        $requireSummationAttributes = [];

        foreach ($this->namesOfAdditionalAttributesRequireSummation as $requireSummationAttribute) {
            $total = self::getTotal($modelCollection, $requireSummationAttribute);
            $requireSummationAttributes[$requireSummationAttribute] = $total;
        }
        return $requireSummationAttributes;
    }

    private function getNotRequireSummationAttributesArray($modelCollection)
    {
        $notRequireSummationAttributes = [];

        foreach ($this->namesOfAdditionalAttributesNotRequireSummation as $notRequireSummationAttribute) {
            $notRequireSummationAttributes[$notRequireSummationAttribute] = $modelCollection[0]->pivot ?
                $modelCollection[0]->pivot->{$notRequireSummationAttribute} :
                $modelCollection[0]->{self::PREVIOUS . $notRequireSummationAttribute};
        }
        return $notRequireSummationAttributes;
    }
}
