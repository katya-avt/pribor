<?php

namespace Database\Seeders\Users;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'id' => 1,
                'name' => 'Катя',
                'email' => 'avt.e@priborvbg.ru',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
                'deleted_at' => null
            ],
            [
                'id' => 2,
                'name' => 'Анна',
                'email' => 'avt.a@priborvbg.ru',
                'password' => Hash::make('12345678'),
                'role_id' => 2,
                'deleted_at' => null
            ],
            [
                'id' => 3,
                'name' => 'Виктория',
                'email' => 'avt.v@priborvbg.ru',
                'password' => Hash::make('12345678'),
                'role_id' => 3,
                'deleted_at' => null
            ],
            [
                'id' => 4,
                'name' => 'Мария',
                'email' => 'avt.m@priborvbg.ru',
                'password' => Hash::make('12345678'),
                'role_id' => 4,
                'deleted_at' => null
            ],
            [
                'id' => 5,
                'name' => 'Ольга',
                'email' => 'avt.o@priborvbg.ru',
                'password' => Hash::make('12345678'),
                'role_id' => 4,
                'deleted_at' => now()
            ]
        ]);
    }
}
