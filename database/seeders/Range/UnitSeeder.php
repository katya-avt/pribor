<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('units')->insert([
            [
                'code' => '006',
                'name' => 'Метр',
                'short_name' => 'м'
            ],
            [
                'code' => '112',
                'name' => 'Литр',
                'short_name' => 'л'
            ],
            [
                'code' => '166',
                'name' => 'Килограмм',
                'short_name' => 'кг'
            ],
            [
                'code' => '168',
                'name' => 'Тонна',
                'short_name' => 'т'
            ],
            [
                'code' => '51',
                'name' => 'Квадратный сантиметр',
                'short_name' => 'см2'
            ],
            [
                'code' => '796',
                'name' => 'Штука',
                'short_name' => 'шт'
            ]
        ]);
    }
}
