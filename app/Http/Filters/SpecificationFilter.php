<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class SpecificationFilter extends AbstractFilter
{
    public const SEARCH = 'search';
    public const DRAWING = 'drawing';

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],
            self::DRAWING => [$this, 'drawing']
        ];
    }

    public function search(Builder $builder, $value)
    {
        $builder->where('number', 'like', "%{$value}%");
    }

    public function drawing(Builder $builder, $value)
    {
        $builder->whereHas('items', function ($query) use ($value) {
            $query->where('drawing', $value);
        });
    }
}
