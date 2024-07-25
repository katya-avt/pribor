<?php

namespace App\Models\Orders;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemRoute extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'order_item_route';
    protected $guarded = false;
}
