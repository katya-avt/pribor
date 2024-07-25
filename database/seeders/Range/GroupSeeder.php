<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            [
                'id' => 1,
                'name' => 'Детали',
                'group_id' => null
            ],
            [
                'id' => 2,
                'name' => 'Крепеж',
                'group_id' => null
            ],
            [
                'id' => 3,
                'name' => 'Материалы',
                'group_id' => null
            ],
            [
                'id' => 4,
                'name' => 'Покрытия',
                'group_id' => null
            ],
            [
                'id' => 5,
                'name' => 'Сборочные единицы',
                'group_id' => null
            ],
            [
                'id' => 6,
                'name' => 'Кабельные изделия',
                'group_id' => 3
            ],
            [
                'id' => 7,
                'name' => 'Металлы',
                'group_id' => 3
            ],
            [
                'id' => 8,
                'name' => 'Пластмассы',
                'group_id' => 3
            ],
            [
                'id' => 9,
                'name' => 'Химикаты',
                'group_id' => 3
            ],
            [
                'id' => 10,
                'name' => 'Разные материалы',
                'group_id' => 3
            ],
            [
                'id' => 11,
                'name' => 'Гальванические покрытия',
                'group_id' => 4
            ],
            [
                'id' => 12,
                'name' => 'Лакокрасочные покрытия',
                'group_id' => 4
            ]
        ]);
    }
}
