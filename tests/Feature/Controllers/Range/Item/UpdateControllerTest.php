<?php

namespace Tests\Feature\Controllers\Range\Item;

use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\ItemType;
use App\Models\Range\MainWarehouse;
use App\Models\Range\ManufactureType;
use App\Models\Range\Unit;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function a_detail_can_be_updated()
    {
        $data = [
            'drawing' => 'Деталь',
            'name' => 'Деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ];

        $item = Item::factory()->create($data);

        $newData = [
            'item' => [
                'drawing' => 'Деталь updated',
                'name' => 'Деталь updated',
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

        $response = $this->patch("/items/{$item->id}", $newData);
        $item->refresh();

        $itemDataFromForm = $newData['item'];
        $itemData = $itemDataFromForm;

        $itemData['item_type_id'] = ItemType::getItemTypeIdByItemTypeName($itemDataFromForm['item_type_id']);
        $itemData['group_id'] = Group::getGroupIdByGroupName($itemDataFromForm['group_id']);
        $itemData['unit_code'] = Unit::getUnitCodeByUnitShortName($itemDataFromForm['unit_code']);
        $itemData['main_warehouse_code'] = MainWarehouse::getMainWarehouseCodeByMainWarehouseName($itemDataFromForm['main_warehouse_code']);
        $itemData['manufacture_type_id'] = ManufactureType::getManufactureTypeIdByManufactureTypeName($itemDataFromForm['manufacture_type_id']);

        $this->assertDatabaseHas('items', ['id' => $item->id] + $itemData);
        $this->assertDatabaseHas('details', ['item_id' => $item->id] + $newData['detail']);

        $response->assertRedirect('/items');

        $response = $this->get('/items');
        $response->assertSee(__('messages.successful_update'));
    }

    /** @test */
    public function a_purchased_item_can_be_updated()
    {
        $data = [
            'drawing' => 'Металл',
            'name' => 'Металл',
            'item_type_id' => 'Покупной',
            'group_id' => 'Металлы',
            'unit_code' => 'кг',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ];

        $item = Item::factory()->create($data);

        $newData = [
            'item' => [
                'drawing' => 'Металл updated',
                'name' => 'Металл updated',
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
                'unit_code' => 'т'
            ]
        ];

        $response = $this->patch("/items/{$item->id}", $newData);
        $item->refresh();

        $itemDataFromForm = $newData['item'];
        $itemData = $itemDataFromForm;

        $itemData['item_type_id'] = ItemType::getItemTypeIdByItemTypeName($itemDataFromForm['item_type_id']);
        $itemData['group_id'] = Group::getGroupIdByGroupName($itemDataFromForm['group_id']);
        $itemData['unit_code'] = Unit::getUnitCodeByUnitShortName($itemDataFromForm['unit_code']);
        $itemData['main_warehouse_code'] = MainWarehouse::getMainWarehouseCodeByMainWarehouseName($itemDataFromForm['main_warehouse_code']);
        $itemData['manufacture_type_id'] = ManufactureType::getManufactureTypeIdByManufactureTypeName($itemDataFromForm['manufacture_type_id']);

        $this->assertDatabaseHas('items', ['id' => $item->id] + $itemData);

        $purchasedItemDataFromForm = $newData['purchased'];
        $purchasedItemData = $purchasedItemDataFromForm;

        $purchasedItemData['unit_code'] = Unit::getUnitCodeByUnitShortName($purchasedItemDataFromForm['unit_code']);

        $this->assertDatabaseHas('purchased_items', ['item_id' => $item->id] + $purchasedItemData);

        $response->assertRedirect('/items');

        $response = $this->get('/items');
        $response->assertSee(__('messages.successful_update'));
    }
}
