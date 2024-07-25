<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
            [
                'id' => 1,
                'name' => 'Механический цех'
            ],
            [
                'id' => 2,
                'name' => 'Сборочный цех'
            ],
            [
                'id' => 3,
                'name' => 'Гальванический цех'
            ],
            [
                'id' => 4,
                'name' => 'Заготовительный цех'
            ],
            [
                'id' => 5,
                'name' => 'ОТК'
            ]
        ]);
    }
}
