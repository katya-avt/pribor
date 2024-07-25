<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rates')->insert([
            [
                'code' => 'С1',
                'name' => 'Базовая тарифная ставка',
                'factor' => 1.00
            ],
            [
                'code' => 'С2',
                'name' => 'Повышенная тарифная ставка',
                'factor' => 1.50
            ],
            [
                'code' => 'С3',
                'name' => 'Опытная тарифная ставка',
                'factor' => 2.00
            ]
        ]);
    }
}
