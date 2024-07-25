<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            [
                'id' => 1,
                'name' => 'Админ'
            ],
            [
                'id' => 2,
                'name' => 'Сотрудник КТО'
            ],
            [
                'id' => 3,
                'name' => 'Сотрудник экономического отдела'
            ],
            [
                'id' => 4,
                'name' => 'Сотрудник отдела снабжения и сбыта'
            ]
        ]);
    }
}
