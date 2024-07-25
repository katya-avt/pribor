<?php

namespace Tests\Feature\Requests\Range\Item;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_drawing_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => null,
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_drawing_has_less_than_2_characters' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Д',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_drawing_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => str_repeat('Д', 256),
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_name_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => null,
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_name_has_less_than_2_characters' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Д',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_name_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => str_repeat('Д', 256),
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_item_type_id_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => null,
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_item_type_id_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Значение не из списка.',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_item_type_id_is_purchased' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_fastener_item_type_id_is_not_purchased' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Крепеж',
                        'name' => 'Крепеж',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Крепеж',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_assembly_item_item_type_id_is_not_proprietary' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Сборочная единица',
                        'name' => 'Сборочная единица',
                        'item_type_id' => 'Давальческий',
                        'group_id' => 'Сборочные единицы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_cable_item_item_type_id_is_not_purchased' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Кабельное изделие',
                        'name' => 'Кабельное изделие',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Кабельные изделия',
                        'unit_code' => 'м',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_metal_item_type_id_is_not_purchased' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_plastic_item_type_id_is_not_purchased' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Пластмасса',
                        'name' => 'Пластмасса',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Пластмассы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_chemical_item_type_id_is_not_purchased' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Химикат',
                        'name' => 'Химикат',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Химикаты',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_various_item_type_id_is_proprietary' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Разный материал',
                        'name' => 'Разный материал',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Разные материалы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_galvanic_item_type_id_is_tolling' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Гальваническое покрытие',
                        'name' => 'Гальваническое покрытие',
                        'item_type_id' => 'Давальческий',
                        'group_id' => 'Гальванические покрытия',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_paint_item_type_id_is_not_purchased' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Лакокрасочное покрытие',
                        'name' => 'Лакокрасочное покрытие',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Лакокрасочные покрытия',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_group_id_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => null,
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_group_id_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Значение не из списка.',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_base_unit_code_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => null,
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_base_unit_code_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'Значение не из списка.',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_base_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_fastener_base_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Крепеж',
                        'name' => 'Крепеж',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Крепеж',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_assembly_item_base_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Сборочная единица',
                        'name' => 'Сборочная единица',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Сборочные единицы',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_cable_item_base_unit_code_is_not_meter' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Кабельное изделие',
                        'name' => 'Кабельное изделие',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Кабельные изделия',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'м'
                    ]
                ]
            ],

            'request_should_fail_when_metal_base_unit_code_is_not_kg' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_plastic_base_unit_code_is_not_kg' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Пластмасса',
                        'name' => 'Пластмасса',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Пластмассы',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_chemical_base_unit_code_is_not_liter' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Химикат',
                        'name' => 'Химикат',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Химикаты',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_various_base_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Разный материал',
                        'name' => 'Разный материал',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Разные материалы',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_galvanic_base_unit_code_is_not_liter' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Гальваническое покрытие',
                        'name' => 'Гальваническое покрытие',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Гальванические покрытия',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_paint_base_unit_code_is_not_liter' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Лакокрасочное покрытие',
                        'name' => 'Лакокрасочное покрытие',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Лакокрасочные покрытия',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 250,
                        'purchase_lot' => 50,
                        'order_point' => 5,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_main_warehouse_code_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => null,
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_main_warehouse_code_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Значение не из списка.',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_manufacture_type_id_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => null
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_manufacture_type_id_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Значение не из списка.'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_is_not_provided_for_detail' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_have_not_first_measurement' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_have_not_second_measurement' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_have_not_third_measurement' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_first_measurement_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '1аx11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_second_measurement_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x1аx12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_third_measurement_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x1а',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_has_not_separator_between_first_and_second_measurements' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '1011x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_has_not_separator_between_second_and_third_measurements' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x1112',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_has_more_than_32_characters' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12' . str_repeat('2', 32),
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_detail_size_is_provided_for_not_detail' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Сборочная единица',
                        'name' => 'Сборочная единица',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Сборочные единицы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_is_not_provided_for_detail' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_have_not_first_measurement' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_have_not_second_measurement' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_have_not_third_measurement' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_first_measurement_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '1аx14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_second_measurement_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x1аx15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_third_measurement_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x1а'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_has_not_separator_between_first_and_second_measurements' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '1314x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_has_not_separator_between_second_and_third_measurements' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x1415'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_has_more_than_32_characters' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15' . str_repeat('5', 32)
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_billet_size_is_provided_for_not_detail' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Сборочная единица',
                        'name' => 'Сборочная единица',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Сборочные единицы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_purchase_price_is_not_provided_for_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_price_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => '79.26а',
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_price_less_than_0' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => -79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_price_greater_than_99999999' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 1000000000,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_price_is_provided_for_not_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_purchase_lot_is_not_provided_for_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => null,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_lot_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => '37.16а',
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_lot_less_than_0' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => -37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_lot_greater_than_99999999' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 1000000000,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_lot_is_not_integer_for_unit_lot' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Разный материал',
                        'name' => 'Разный материал',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Разные материалы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_purchase_lot_is_provided_for_not_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => 37,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_order_point_is_not_provided_for_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => null,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_order_point_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => '25.58а',
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_order_point_less_than_0' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => -25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_order_point_greater_than_99999999' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 1000000000,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_order_point_is_not_integer_for_unit_lot' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Разный материал',
                        'name' => 'Разный материал',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Разные материалы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37,
                        'order_point' => 25.58,
                        'unit_factor' => 1,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_order_point_is_provided_for_not_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => 25,
                        'unit_factor' => null,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_unit_factor_is_not_provided_for_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => null,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_unit_factor_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => '1000а',
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_unit_factor_less_than_0' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => -1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_unit_factor_greater_than_9999' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 100000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_unit_factor_is_not_equal_to_1_when_units_match' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Разный материал',
                        'name' => 'Разный материал',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Разные материалы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37,
                        'order_point' => 25,
                        'unit_factor' => 1000,
                        'unit_code' => 'шт'
                    ]
                ]
            ],

            'request_should_fail_when_unit_factor_is_not_equal_to_1000_when_units_are_kg_and_ton' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 20,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_unit_factor_is_provided_for_not_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => 1000,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_purchased_unit_code_is_not_provided_for_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => null
                    ]
                ]
            ],

            'request_should_fail_when_purchased_unit_code_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'Значение не из списка.'
                    ]
                ]
            ],

            'request_should_fail_when_detail_purchased_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => 'л'
                    ]
                ]
            ],

            'request_should_fail_when_fastener_purchased_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Крепеж',
                        'name' => 'Крепеж',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Крепеж',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37,
                        'order_point' => 25,
                        'unit_factor' => 1,
                        'unit_code' => 'л'
                    ]
                ]
            ],

            'request_should_fail_when_assembly_item_purchased_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Сборочная единица',
                        'name' => 'Сборочная единица',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Сборочные единицы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => 'л'
                    ]
                ]
            ],

            'request_should_fail_when_cable_item_purchased_unit_code_is_not_meter' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Кабельное изделие',
                        'name' => 'Кабельное изделие',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Кабельные изделия',
                        'unit_code' => 'м',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'л'
                    ]
                ]
            ],

            'request_should_fail_when_metal_purchased_unit_code_is_not_ton' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Металл',
                        'name' => 'Металл',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Металлы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'л'
                    ]
                ]
            ],

            'request_should_fail_when_plastic_purchased_unit_code_is_not_ton' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Пластмасса',
                        'name' => 'Пластмасса',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Пластмассы',
                        'unit_code' => 'кг',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37.16,
                        'order_point' => 25.58,
                        'unit_factor' => 1000,
                        'unit_code' => 'л'
                    ]
                ]
            ],

            'request_should_fail_when_chemical_purchased_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Химикат',
                        'name' => 'Химикат',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Химикаты',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37,
                        'order_point' => 25,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_various_purchased_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Разный материал',
                        'name' => 'Разный материал',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Разные материалы',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37,
                        'order_point' => 25,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_galvanic_purchased_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Гальваническое покрытие',
                        'name' => 'Гальваническое покрытие',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Гальванические покрытия',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37,
                        'order_point' => 25,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_paint_purchased_unit_code_is_not_unit' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Лакокрасочное покрытие',
                        'name' => 'Лакокрасочное покрытие',
                        'item_type_id' => 'Покупной',
                        'group_id' => 'Лакокрасочные покрытия',
                        'unit_code' => 'л',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => null,
                        'billet_size' => null
                    ],
                    'purchased' => [
                        'purchase_price' => 79.26,
                        'purchase_lot' => 37,
                        'order_point' => 25,
                        'unit_factor' => 1000,
                        'unit_code' => 'т'
                    ]
                ]
            ],

            'request_should_fail_when_purchased_unit_code_is_provided_for_not_purchased_item' => [
                'passed' => false,
                'data' => [
                    'item' => [
                        'drawing' => 'Деталь',
                        'name' => 'Деталь',
                        'item_type_id' => 'Собственный',
                        'group_id' => 'Детали',
                        'unit_code' => 'шт',
                        'main_warehouse_code' => 'Склад материалов 1 цеха',
                        'manufacture_type_id' => 'Страховой запас'
                    ],
                    'detail' => [
                        'detail_size' => '10x11x12',
                        'billet_size' => '13x14x15'
                    ],
                    'purchased' => [
                        'purchase_price' => null,
                        'purchase_lot' => null,
                        'order_point' => null,
                        'unit_factor' => null,
                        'unit_code' => 'шт'
                    ]
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     */
    public function validation_results_as_expected($shouldPass)
    {
        $from = $this->from('/items/create');

        $response = $from->post('/items');

        if ($shouldPass) {
            $response->assertRedirect('/items');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_drawing_is_not_unique()
    {
        $item = Item::first();

        $data = [
            'item' => [
                'drawing' => $item->drawing,
                'name' => 'Деталь',
                'item_type_id' => 'Собственный',
                'group_id' => 'Детали',
                'unit_code' => 'шт',
                'main_warehouse_code' => 'Склад материалов 1 цеха',
                'manufacture_type_id' => 'Страховой запас'
            ],
            'detail' => [
                'detail_size' => '10x11x12',
                'billet_size' => '13x14x15'
            ],
            'purchased' => [
                'purchase_price' => null,
                'purchase_lot' => null,
                'order_point' => null,
                'unit_factor' => null,
                'unit_code' => null
            ]
        ];

        $from = $this->from('/items/create');

        $response = $from->post('/items', $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_name_is_not_unique()
    {
        $item = Item::first();

        $data = [
            'item' => [
                'drawing' => 'Деталь',
                'name' => $item->name,
                'item_type_id' => 'Собственный',
                'group_id' => 'Детали',
                'unit_code' => 'шт',
                'main_warehouse_code' => 'Склад материалов 1 цеха',
                'manufacture_type_id' => 'Страховой запас'
            ],
            'detail' => [
                'detail_size' => '10x11x12',
                'billet_size' => '13x14x15'
            ],
            'purchased' => [
                'purchase_price' => null,
                'purchase_lot' => null,
                'order_point' => null,
                'unit_factor' => null,
                'unit_code' => null
            ]
        ];

        $from = $this->from('/items/create');

        $response = $from->post('/items', $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
