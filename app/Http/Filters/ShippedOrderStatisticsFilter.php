<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class ShippedOrderStatisticsFilter extends AbstractFilter
{
    public const PERIOD = 'period';
    public const QUARTER = 'quarter';
    public const MONTH = 'month';
    public const DATE = 'date';
    public const FROM_DATE = 'from_date';
    public const TO_DATE = 'to_date';

    private string $fromDate = '';

    protected function getCallbacks(): array
    {
        return [
            self::PERIOD => [$this, 'period'],
            self::QUARTER => [$this, 'quarter'],
            self::MONTH => [$this, 'month'],
            self::DATE => [$this, 'date'],
            self::FROM_DATE => [$this, 'fromDate'],
            self::TO_DATE => [$this, 'toDate']
        ];
    }

    public function period(Builder $builder, $value)
    {
        $startOfValue = Carbon::now();

        switch ($value) {
            case 'year':
                $startOfValue = $startOfValue->startOfYear();
                break;
            case 'quarter':
                $startOfValue = $startOfValue->startOfQuarter();
                break;
            case 'month':
                $startOfValue = $startOfValue->startOfMonth();
                break;
        }

        $currentDate = Carbon::now();

        $builder->whereBetween('completion_date', [$startOfValue, $currentDate]);
    }

    public function quarter(Builder $builder, $value)
    {
        $startOfQuarter = Carbon::createFromFormat('Y-m', $value)->startOfQuarter();
        $endOfQuarter = $startOfQuarter->copy()->endOfQuarter();

        $builder->whereBetween('completion_date', [$startOfQuarter, $endOfQuarter]);
    }

    public function month(Builder $builder, $value)
    {
        $startOfMonth = Carbon::createFromFormat('Y-m', $value)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $builder->whereBetween('completion_date', [$startOfMonth, $endOfMonth]);
    }

    public function date(Builder $builder, $value)
    {
        $builder->where('completion_date', $value);
    }

    public function fromDate(Builder $builder, $value)
    {
        $this->fromDate = $value;
    }

    public function toDate(Builder $builder, $value)
    {
        $builder->whereBetween('completion_date', [$this->fromDate, $value]);
    }
}
