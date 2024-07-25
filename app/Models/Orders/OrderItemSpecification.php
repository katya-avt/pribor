<?php

namespace App\Models\Orders;

use App\Models\Range\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItemSpecification extends Model
{
    use HasFactory;

    public $timestamps = false;
    public $incrementing = false;
    protected $table = 'order_item_specification';
    protected $primaryKey = 'order_item_specification_id';
    protected $keyType = 'string';
    protected $guarded = false;

    public function items()
    {
        return $this->hasMany(OrderItemSpecification::class, 'order_item_specification_parent_id');
    }

    public function descendants()
    {
        return $this->hasMany(OrderItemSpecification::class, 'order_item_specification_parent_id')->with('items');
    }

    public function ancestor()
    {
        return $this->belongsTo(OrderItemSpecification::class, 'order_item_specification_parent_id');
    }

    public function ancestors()
    {
        return $this->ancestor()->with('ancestors');
    }

    public function component()
    {
        return $this->belongsTo(Item::class, 'component_id', 'id');
    }

    public function orderItem()
    {
        return $this->belongsTo(Item::class, 'item_id', 'id');
    }
}
