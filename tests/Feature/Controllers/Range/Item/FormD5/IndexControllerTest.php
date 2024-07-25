<?php

namespace Tests\Feature\Controllers\Range\Item\FormD5;

use App\Models\Range\CoverItem;
use App\Models\Range\Item;
use App\Models\Range\SpecificationItem;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
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
    public function response_for_route_items_form_d5_index_is_view_range_items_form_d5_index_with_items_that_contain_cover_item()
    {
        $assemblyItems = collect();

        $paintItem = Item::factory()->create([
            'drawing' => 'Лакокрасочное покрытие',
            'name' => 'Лакокрасочное покрытие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Лакокрасочные покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        for ($i = 1; $i <= 2; $i++) {
            $assemblyItem = Item::factory()->create([
                'drawing' => 'Тест Сборочная единица' . '_' . $i,
                'name' => 'Тест Сборочная единица' . '_' . $i,
                'item_type_id' => 'Собственный',
                'group_id' => 'Сборочные единицы',
                'unit_code' => 'шт',
                'main_warehouse_code' => 'Межцеховой склад 2 цеха',
                'manufacture_type_id' => 'Страховой запас',
                'specification_number' => '10000000' . $i,
                'cover_number' => '10000000' . $i,
                'route_number' => '10000000' . $i
            ]);

            $assemblyItems->push($assemblyItem);
        }

        $coverItems = collect();

        foreach ($assemblyItems as $assemblyItem) {
            $coverItem = CoverItem::factory()->create([
                'cover_number' => $assemblyItem->cover_number,
                'item_id' => $paintItem->id
            ]);

            $coverItems->push($coverItem);
        }

        $response = $this->get("/items/{$paintItem->id}/form-d5");
        $response->assertOk();

        $response->assertViewIs('range.items.form-d5.index');

        $response->assertSee($paintItem->drawing);
        $response->assertSee($paintItem->name);

        foreach ($assemblyItems->zip($coverItems) as $assemblyItemCoverItem) {
            $assemblyItem = $assemblyItemCoverItem[0];
            $coverItem = $assemblyItemCoverItem[1];

            $response->assertSee($assemblyItem->drawing);
            $response->assertSee($assemblyItem->name);

            $response->assertSee($coverItem->area * $coverItem->consumption);

            $response->assertSee($paintItem->unit->short_name);

            $response->assertSee($assemblyItem->cover_number);
        }
    }

    /** @test */
    public function response_for_route_items_form_d5_index_is_view_range_items_form_d5_index_with_items_that_contain_item()
    {
        $assemblyItems = collect();

        $detailItem = Item::factory()->create([
            'drawing' => 'Деталь',
            'name' => 'Деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Межцеховой склад 2 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        for ($i = 1; $i <= 2; $i++) {
            $assemblyItem = Item::factory()->create([
                'drawing' => 'Тест Сборочная единица' . '_' . $i,
                'name' => 'Тест Сборочная единица' . '_' . $i,
                'item_type_id' => 'Собственный',
                'group_id' => 'Сборочные единицы',
                'unit_code' => 'шт',
                'main_warehouse_code' => 'Межцеховой склад 2 цеха',
                'manufacture_type_id' => 'Страховой запас',
                'specification_number' => '10000000' . $i,
                'cover_number' => '10000000' . $i,
                'route_number' => '10000000' . $i
            ]);

            $assemblyItems->push($assemblyItem);
        }

        $specificationItems = collect();

        foreach ($assemblyItems as $assemblyItem) {
            $specificationItem = SpecificationItem::factory()->create([
                'specification_number' => $assemblyItem->specification_number,
                'item_id' => $detailItem->id
            ]);

            $specificationItems->push($specificationItem);
        }

        $response = $this->get("/items/{$detailItem->id}/form-d5");
        $response->assertOk();

        $response->assertViewIs('range.items.form-d5.index');

        $response->assertSee($detailItem->drawing);
        $response->assertSee($detailItem->name);

        foreach ($assemblyItems->zip($specificationItems) as $assemblyItemSpecificationItem) {
            $assemblyItem = $assemblyItemSpecificationItem[0];
            $specificationItem = $assemblyItemSpecificationItem[1];

            $response->assertSee($assemblyItem->drawing);
            $response->assertSee($assemblyItem->name);

            $response->assertSee($specificationItem->cnt);

            $response->assertSee($detailItem->unit->short_name);

            $response->assertSee($assemblyItem->specification_number);
        }
    }
}
