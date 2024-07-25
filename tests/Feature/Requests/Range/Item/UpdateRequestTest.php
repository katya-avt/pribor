<?php

namespace Tests\Feature\Requests\Range\Item;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
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
        return (new StoreRequestTest())->validationProvider();
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $item = Item::whereNull('added_to_order_at')->first();

        $from = $this->from("/items/{$item->id}/edit");

        $response = $from->patch("/items/{$item->id}", $mockedRequestData);

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
        $firstItem = Item::whereNull('added_to_order_at')->first();
        $secondItem = Item::whereNull('added_to_order_at')->where('id', '<>', $firstItem->id)->first();

        $data = [
            'item' => [
                'drawing' => $secondItem->drawing,
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

        $from = $this->from("/items/{$firstItem->id}/edit");

        $response = $from->patch("/items/{$firstItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_name_is_not_unique()
    {
        $firstItem = Item::whereNull('added_to_order_at')->first();
        $secondItem = Item::whereNull('added_to_order_at')->where('id', '<>', $firstItem->id)->first();

        $data = [
            'item' => [
                'drawing' => 'Деталь',
                'name' => $secondItem->name,
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

        $from = $this->from("/items/{$firstItem->id}/edit");

        $response = $from->patch("/items/{$firstItem->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_drawing_is_not_updated()
    {
        $item = Item::has('detail')->whereNull('added_to_order_at')->first();

        $updatedItemData = [
            'item' => [
                'drawing' => $item->drawing,
                'name' => 'Деталь2',
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

        $from = $this->from("/items/{$item->id}/edit");

        $response = $from->patch("/items/{$item->id}", $updatedItemData);
        $response->assertRedirect("/items");
    }

    /** @test */
    public function request_should_not_fail_when_unique_name_is_not_updated()
    {
        $item = Item::has('detail')->whereNull('added_to_order_at')->first();

        $updatedItemData = [
            'item' => [
                'drawing' => 'Деталь2',
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

        $from = $this->from("/items/{$item->id}/edit");

        $response = $from->patch("/items/{$item->id}", $updatedItemData);
        $response->assertRedirect("/items");
    }
}
