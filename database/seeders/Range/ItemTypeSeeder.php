<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ItemTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('item_types')->insert([
            [
                'id' => 1,
                'name' => 'Собственный'
            ],
            [
                'id' => 2,
                'name' => 'Покупной'
            ],
            [
                'id' => 3,
                'name' => 'Давальческий'
            ]
        ]);
    }
}
