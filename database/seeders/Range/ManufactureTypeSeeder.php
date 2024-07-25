<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManufactureTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('manufacture_types')->insert([
            [
                'id' => 1,
                'name' => 'Под заказ'
            ],
            [
                'id' => 2,
                'name' => 'Страховой запас'
            ]
        ]);
    }
}
