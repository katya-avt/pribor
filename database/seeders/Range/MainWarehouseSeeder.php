<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MainWarehouseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('main_warehouses')->insert([
            [
                'code' => 'СГП',
                'name' => 'Склад готовой продукции'
            ],
            [
                'code' => 'Ц1_Материал',
                'name' => 'Склад материалов 1 цеха'
            ],
            [
                'code' => 'Ц1_Транзит',
                'name' => 'Транзитный склад 1 цеха'
            ],
            [
                'code' => 'Ц2_Комплект',
                'name' => 'Склад комплектующих 2 цеха'
            ],
            [
                'code' => 'Ц2_Материал',
                'name' => 'Склад материалов 2 цеха'
            ],
            [
                'code' => 'Ц2_Межцех',
                'name' => 'Межцеховой склад 2 цеха'
            ],
            [
                'code' => 'Ц2_Транзит',
                'name' => 'Транзитный склад 2 цеха'
            ],
            [
                'code' => 'Ц3_Материал',
                'name' => 'Склад материалов 3 цеха'
            ],
            [
                'code' => 'Ц3_Межцех',
                'name' => 'Межцеховой склад 3 цеха'
            ]
        ]);
    }
}
