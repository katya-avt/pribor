<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\PurchasedItemFilter;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PurchasedItemFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function filterProvider()
    {
        return [
            'purchased_items_can_be_filtered_by_drawing_or_name' => [
                'data' => [
                    'search' => 'Лак'
                ],
                'includedItem' => 'Лакокрасочное покрытие',
                'excludedItem' => 'Крепеж'
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

        $fastenerItem = Item::factory()->create([
            'drawing' => 'Крепеж',
            'name' => 'Крепеж',
            'item_type_id' => 'Покупной',
            'group_id' => 'Крепеж',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        $filter = app()->make(PurchasedItemFilter::class, ['queryParams' => array_filter($mockedFilterData)]);

        $purchasedItems = Item::has('purchasedItem')->with('purchasedItem')->filter($filter)->get();

        $includedItem = Item::has('purchasedItem')->where('drawing', $includedItem)->first();
        $excludedItem = Item::has('purchasedItem')->where('drawing', $excludedItem)->first();

        $this->assertTrue($purchasedItems->contains($includedItem));
        $this->assertFalse($purchasedItems->contains($excludedItem));
    }
}
