<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'customers';
    protected $primaryKey = 'inn';
    protected $keyType = 'string';
    public $incrementing = false;
    protected $guarded = false;

    public static function getCustomerInnByCustomerName(string $customerName): string
    {
        return self::query()->select('inn')->where('name', $customerName)->first()['inn'];
    }
}
