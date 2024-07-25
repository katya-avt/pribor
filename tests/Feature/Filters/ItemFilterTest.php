<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\ItemFilter;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ItemFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function filterProvider()
    {
        return [
            'items_can_be_filtered_by_drawing_or_name' => [
                'data' => [
                    'search' => 'Лак',
                    'group_id' => null,
                    'item_type_id' => null,
                    'main_warehouse_code' => null,
                    'manufacture_type_id' => null
                ],
                'includedItem' => 'Лакокрасочное покрытие',
                'excludedItem' => 'Деталь'
            ],

            'items_can_be_filtered_by_group' => [
                'data' => [
                    'search' => null,
                    'group_id' => 'Лакокрасочные покрытия',
                    'item_type_id' => null,
                    'main_warehouse_code' => null,
                    'manufacture_type_id' => null
                ],
                'includedItem' => 'Лакокрасочное покрытие',
                'excludedItem' => 'Деталь'
            ],

            'items_can_be_filtered_by_item_type' => [
                'data' => [
                    'search' => null,
                    'group_id' => null,
                    'item_type_id' => 'Собственный',
                    'main_warehouse_code' => null,
                    'manufacture_type_id' => null
                ],
                'includedItem' => 'Деталь',
                'excludedItem' => 'Лакокрасочное покрытие'
            ],

            'items_can_be_filtered_by_main_warehouse' => [
                'data' => [
                    'search' => null,
                    'group_id' => null,
                    'item_type_id' => null,
                    'main_warehouse_code' => 'Склад готовой продукции',
                    'manufacture_type_id' => null
                ],
                'includedItem' => 'Деталь',
                'excludedItem' => 'Лакокрасочное покрытие'
            ],

            'items_can_be_filtered_by_manufacture_type' => [
                'data' => [
                    'search' => null,
                    'group_id' => null,
                    'item_type_id' => null,
                    'main_warehouse_code' => null,
                    'manufacture_type_id' => 'Страховой запас'
                ],
                'includedItem' => 'Лакокрасочное покрытие',
                'excludedItem' => 'Деталь'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider filterProvider
     * @param array $mockedFilterData
     * @param string $includedItem
     * @param string $excludedItem
     */
    public function filter_results_as_expected($mockedFilterData, $includedItem, $excludedItem)
    {
        $paintItem = Item::factory()->create([
            'drawing' => 'Лакокрасочное покрытие',
            'name' => 'Лакокрасочное покрытие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Лакокрасочные покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        $detailItem = Item::factory()->create([
            'drawing' => 'Деталь',
            'name' => 'Деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад готовой продукции',
            'manufacture_type_id' => 'Под заказ'
        ]);

        $filter = app()->make(ItemFilter::class, ['queryParams' => array_filter($mockedFilterData)]);

        $items = Item::with('group', 'itemType', 'unit', 'manufactureType')
            ->filter($filter)->get();

        $includedItem = Item::getItemByDrawing($includedItem);
        $excludedItem = Item::getItemByDrawing($excludedItem);

        $this->assertTrue($items->contains($includedItem));
        $this->assertFalse($items->contains($excludedItem));
    }
}
