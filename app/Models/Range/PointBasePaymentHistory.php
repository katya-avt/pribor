<?php

namespace App\Models\Range;

use App\Models\Traits\Filterable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PointBasePaymentHistory extends Model
{
    use HasFactory;
    use Filterable;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'point_base_payment_history';
    protected $guarded = false;

    protected function changeTime(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => Carbon::parse($value)->format('d.m.Y H:i:s'),
        );
    }
}
