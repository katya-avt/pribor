<?php

namespace Database\Seeders\Orders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class OrderStatusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('order_statuses')->insert([
            [
                'id' => 1,
                'name' => 'Отложен',
                'url_param_name' => 'pending'
            ],
            [
                'id' => 2,
                'name' => 'В производстве',
                'url_param_name' => 'in-production'
            ],
            [
                'id' => 3,
                'name' => 'Произведен',
                'url_param_name' => 'production-completed'
            ],
            [
                'id' => 4,
                'name' => 'На отгрузке',
                'url_param_name' => 'on-shipment'
            ],
            [
                'id' => 5,
                'name' => 'Отгружен',
                'url_param_name' => 'shipped'
            ]
        ]);
    }
}
