<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class OrderFilter extends AbstractFilter
{
    public const SEARCH = 'search';
    public const CUSTOMER_INN = 'customer_inn';
    public const DRAWING = 'drawing';
    public const SORT_BY = 'sort_by';
    public const SORT_DIRECTION = 'sort_direction';

    private string $column = '';

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],
            self::CUSTOMER_INN => [$this, 'customerInn'],
            self::DRAWING => [$this, 'drawing'],
            self::SORT_BY => [$this, 'sortBy'],
            self::SORT_DIRECTION => [$this, 'sortDirection']
        ];
    }

    public function search(Builder $builder, $value)
    {
        $builder->where('code', 'like', "%{$value}%")
            ->orWhere('name', 'like', "%{$value}%");
    }

    public function customerInn(Builder $builder, $value)
    {
        $builder->whereHas('customer', function ($query) use ($value) {
            $query->where('name', $value);
        });
    }

    public function drawing(Builder $builder, $value)
    {
        $builder->whereHas('items', function ($query) use ($value) {
            $query->where('drawing', $value);
        });
    }

    public function sortBy(Builder $builder, $value)
    {
        $this->column = $value;
    }

    public function sortDirection(Builder $builder, $value)
    {
        $builder->orderBy($this->column, $value);
    }
}
