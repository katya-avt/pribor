<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;

class RouteFilter extends AbstractFilter
{
    public const SEARCH = 'search';
    public const POINT_CODE = 'point_code';
    public const OPERATION_CODE = 'operation_code';

    protected function getCallbacks(): array
    {
        return [
            self::SEARCH => [$this, 'search'],
            self::POINT_CODE => [$this, 'pointCode'],
            self::OPERATION_CODE => [$this, 'operationCode']
        ];
    }

    public function search(Builder $builder, $value)
    {
        $builder->where('number', 'like', "%{$value}%");
    }

    public function pointCode(Builder $builder, $value)
    {
        $builder->whereHas('points', function ($query) use ($value) {
            $query->where('code', $value);
        });
    }

    public function operationCode(Builder $builder, $value)
    {
        $builder->whereHas('operations', function ($query) use ($value) {
            $query->where('code', $value);
        });
    }
}
