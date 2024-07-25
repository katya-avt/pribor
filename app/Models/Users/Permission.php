<?php

namespace App\Models\Users;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{
    use HasFactory;

    public $timestamps = false;
    protected $table = 'permissions';
    protected $guarded = false;

    const ITEMS_VIEW = 'items-view';
    const ITEMS_MANAGE = 'items-manage';
    const SPECIFICATIONS_VIEW = 'specifications-view';
    const SPECIFICATIONS_MANAGE = 'specifications-manage';
    const COVERS_VIEW = 'covers-view';
    const COVERS_MANAGE = 'covers-manage';
    const ROUTES_VIEW = 'routes-view';
    const ROUTES_MANAGE = 'routes-manage';
    const PENDING_ORDERS_VIEW = 'pending-orders-view';
    const PUT_ORDER_INTO_PRODUCTION = 'put-order-into-production';
    const IN_PRODUCTION_ORDERS_VIEW = 'in-production-orders-view';
    const COMPLETE_ORDER_PRODUCTION = 'complete-order-production';
    const PRODUCTION_COMPLETED_ORDERS_VIEW = 'production-completed-orders-view';
    const SEND_ORDER_ON_SHIPMENT = 'send-order-on-shipment';
    const ON_SHIPMENT_ORDERS_VIEW = 'on-shipment-orders-view';
    const SHIP_ORDER = 'ship-order';
    const SHIPPED_ORDERS_VIEW = 'shipped-orders-view';
    const ITEMS_IN_STOCK_VIEW = 'items-in-stock-view';
    const ITEMS_IN_STOCK_MANAGE = 'items-in-stock-manage';
    const ITEMS_CONSUMPTION_VIEW = 'items-consumption-view';
    const ORDER_POINT_VIEW = 'order-point-view';
    const PERIODIC_REQUISITES_VIEW = 'periodic-requisites-view';
    const PERIODIC_REQUISITES_MANAGE = 'periodic-requisites-manage';
    const ORDERS_MANAGE = 'orders-manage';

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
