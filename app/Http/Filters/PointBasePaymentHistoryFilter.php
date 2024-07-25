<?php

namespace App\Http\Filters;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Carbon;

class PointBasePaymentHistoryFilter extends AbstractFilter
{
    public const PERIOD = 'period';
    public const QUARTER = 'quarter';
    public const MONTH = 'month';
    public const DATE = 'date';
    public const FROM_DATE = 'from_date';
    public const TO_DATE = 'to_date';

    private string $startOfFromDate = '';

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

        $builder->whereBetween('change_time', [$startOfValue, $currentDate]);
    }

    public function quarter(Builder $builder, $value)
    {
        $startOfQuarter = Carbon::createFromFormat('Y-m', $value)->startOfQuarter();
        $endOfQuarter = $startOfQuarter->copy()->endOfQuarter();

        $builder->whereBetween('change_time', [$startOfQuarter, $endOfQuarter]);
    }

    public function month(Builder $builder, $value)
    {
        $startOfMonth = Carbon::createFromFormat('Y-m', $value)->startOfMonth();
        $endOfMonth = $startOfMonth->copy()->endOfMonth();

        $builder->whereBetween('change_time', [$startOfMonth, $endOfMonth]);
    }

    public function date(Builder $builder, $value)
    {
        $startOfDay = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
        $endOfDay = $startOfDay->copy()->endOfDay();

        $builder->whereBetween('change_time', [$startOfDay, $endOfDay]);
    }

    public function fromDate(Builder $builder, $value)
    {
        $this->startOfFromDate = Carbon::createFromFormat('Y-m-d', $value)->startOfDay();
    }

    public function toDate(Builder $builder, $value)
    {
        $endOfToDate = Carbon::createFromFormat('Y-m-d', $value)->endOfDay();

        $builder->whereBetween('change_time', [$this->startOfFromDate, $endOfToDate]);
    }
}
