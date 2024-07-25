<?php

namespace Database\Seeders\Range;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PointSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('points')->insert([
            [
                'code' => '1.1',
                'name' => 'Участок прессовки',
                'base_payment' => 5.21,
                'department_id' => 1
            ],
            [
                'code' => '1.2',
                'name' => 'Каркасно-штамповочный участок',
                'base_payment' => 4.70,
                'department_id' => 1
            ],
            [
                'code' => '1.3',
                'name' => 'Литейный участок',
                'base_payment' => 5.21,
                'department_id' => 1
            ],
            [
                'code' => '1.4',
                'name' => 'Токарный участок',
                'base_payment' => 5.21,
                'department_id' => 1
            ],
            [
                'code' => '1.5',
                'name' => 'Токарный с ЧПУ',
                'base_payment' => 6.77,
                'department_id' => 1
            ],
            [
                'code' => '1.6',
                'name' => 'Фрезерный',
                'base_payment' => 6.25,
                'department_id' => 1
            ],
            [
                'code' => '1.7',
                'name' => 'Фрезерный с ЧПУ',
                'base_payment' => 6.77,
                'department_id' => 1
            ],
            [
                'code' => '2',
                'name' => 'Сборка',
                'base_payment' => 6.25,
                'department_id' => 2
            ],
            [
                'code' => '3г',
                'name' => 'Участок гальваники',
                'base_payment' => 6.77,
                'department_id' => 3
            ],
            [
                'code' => '3лг',
                'name' => 'Участок гравировки',
                'base_payment' => 5.21,
                'department_id' => 3
            ],
            [
                'code' => '3п',
                'name' => 'Участок лакокрасочный',
                'base_payment' => 5.73,
                'department_id' => 3
            ],
            [
                'code' => 'ВЖ',
                'name' => 'Вязание жгутов',
                'base_payment' => 4.17,
                'department_id' => 4
            ],
            [
                'code' => 'Зал',
                'name' => 'Заливка',
                'base_payment' => 4.17,
                'department_id' => 4
            ],
            [
                'code' => 'Изол',
                'name' => 'Изолировка',
                'base_payment' => 4.17,
                'department_id' => 4
            ],
            [
                'code' => 'ЛазГрав',
                'name' => 'Лазерная гравировка',
                'base_payment' => 4.70,
                'department_id' => 4
            ],
            [
                'code' => 'ЛазРез',
                'name' => 'Лазерная резка',
                'base_payment' => 4.70,
                'department_id' => 4
            ],
            [
                'code' => 'Марк',
                'name' => 'Маркировка',
                'base_payment' => 4.17,
                'department_id' => 4
            ],
            [
                'code' => 'Монт',
                'name' => 'Монтаж',
                'base_payment' => 5.21,
                'department_id' => 4
            ],
            [
                'code' => 'Нам',
                'name' => 'Намотка',
                'base_payment' => 6.25,
                'department_id' => 4
            ],
            [
                'code' => 'Проп',
                'name' => 'Пропитка',
                'base_payment' => 6.25,
                'department_id' => 4
            ],
            [
                'code' => 'Рег',
                'name' => 'Регулировка',
                'base_payment' => 6.25,
                'department_id' => 4
            ],
            [
                'code' => 'Свар',
                'name' => 'Сварка',
                'base_payment' => 6.77,
                'department_id' => 4
            ],
            [
                'code' => 'Термич',
                'name' => 'Термичка',
                'base_payment' => 4.70,
                'department_id' => 4
            ],
            [
                'code' => 'Уп',
                'name' => 'Упаковка',
                'base_payment' => 4.17,
                'department_id' => 4
            ],
            [
                'code' => 'Чист',
                'name' => 'Чистка',
                'base_payment' => 4.70,
                'department_id' => 4
            ],
            [
                'code' => 'Шлиф',
                'name' => 'Шлифовка',
                'base_payment' => 4.70,
                'department_id' => 4
            ],
            [
                'code' => 'Шт',
                'name' => 'Штамповка',
                'base_payment' => 4.17,
                'department_id' => 4
            ],
            [
                'code' => '5',
                'name' => 'Технический контроль',
                'base_payment' => 5.21,
                'department_id' => 5
            ]
        ]);
    }
}
