<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('permissions')->insert([
            [
                'id' => 1,
                'name' => 'items-view'
            ],
            [
                'id' => 2,
                'name' => 'items-manage'
            ],
            [
                'id' => 3,
                'name' => 'specifications-view'
            ],
            [
                'id' => 4,
                'name' => 'specifications-manage'
            ],
            [
                'id' => 5,
                'name' => 'covers-view'
            ],
            [
                'id' => 6,
                'name' => 'covers-manage'
            ],
            [
                'id' => 7,
                'name' => 'routes-view'
            ],
            [
                'id' => 8,
                'name' => 'routes-manage'
            ],
            [
                'id' => 9,
                'name' => 'pending-orders-view'
            ],
            [
                'id' => 10,
                'name' => 'put-order-into-production'
            ],
            [
                'id' => 11,
                'name' => 'in-production-orders-view'
            ],
            [
                'id' => 12,
                'name' => 'complete-order-production'
            ],
            [
                'id' => 13,
                'name' => 'production-completed-orders-view'
            ],
            [
                'id' => 14,
                'name' => 'send-order-on-shipment'
            ],
            [
                'id' => 15,
                'name' => 'on-shipment-orders-view'
            ],
            [
                'id' => 16,
                'name' => 'ship-order'
            ],
            [
                'id' => 17,
                'name' => 'shipped-orders-view'
            ],
            [
                'id' => 18,
                'name' => 'items-in-stock-view'
            ],
            [
                'id' => 19,
                'name' => 'items-in-stock-manage'
            ],
            [
                'id' => 20,
                'name' => 'items-consumption-view'
            ],
            [
                'id' => 21,
                'name' => 'order-point-view'
            ],
            [
                'id' => 22,
                'name' => 'periodic-requisites-view'
            ],
            [
                'id' => 23,
                'name' => 'periodic-requisites-manage'
            ],
            [
                'id' => 24,
                'name' => 'orders-manage'
            ]
        ]);
    }
}
