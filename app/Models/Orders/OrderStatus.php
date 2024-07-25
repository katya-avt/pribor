<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'order_statuses';
    protected $guarded = false;

    const PENDING = 1;
    const IN_PRODUCTION = 2;
    const PRODUCTION_COMPLETED = 3;
    const ON_SHIPMENT = 4;
    const SHIPPED = 5;

    const PENDING_URL_PARAM_NAME = 'pending';
    const IN_PRODUCTION_URL_PARAM_NAME = 'in-production';
    const PRODUCTION_COMPLETED_URL_PARAM_NAME = 'production-completed';
    const ON_SHIPMENT_URL_PARAM_NAME = 'on-shipment';
    const SHIPPED_URL_PARAM_NAME = 'shipped';
}
