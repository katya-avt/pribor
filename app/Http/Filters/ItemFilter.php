<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class ItemFilter extends AbstractFilter
{
    public const SEARCH = 'search';
    public const GROUP_ID = 'group_id';
    public const ITEM_TYPE_ID = 'item_type_id';
    public const MAIN_WAREHOUSE_CODE = 'main_warehouse_code';
    public const MANUFACTURE_TYPE_ID = 'manufacture_type_id';

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],
            self::GROUP_ID => [$this, 'groupId'],
            self::ITEM_TYPE_ID => [$this, 'itemTypeId'],
            self::MAIN_WAREHOUSE_CODE => [$this, 'mainWarehouseCode'],
            self::MANUFACTURE_TYPE_ID => [$this, 'manufactureTypeId']
        ];
    }

    public function search(Builder $builder, $value)
    {
        $builder->where('drawing', 'like', "%{$value}%")
            ->orWhere('name', 'like', "%{$value}%");
    }

    public function groupId(Builder $builder, $value)
    {
        $builder->whereHas('group', function ($query) use ($value) {
            $query->where('name', $value);
        });
    }

    public function itemTypeId(Builder $builder, $value)
    {
        $builder->whereHas('itemType', function ($query) use ($value) {
            $query->where('name', $value);
        });
    }

    public function mainWarehouseCode(Builder $builder, $value)
    {
        $builder->whereHas('mainWarehouse', function ($query) use ($value) {
            $query->where('name', $value);
        });
    }

    public function manufactureTypeId(Builder $builder, $value)
    {
        $builder->whereHas('manufactureType', function ($query) use ($value) {
            $query->where('name', $value);
        });
    }
}
