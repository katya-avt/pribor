<?php

namespace Tests\Feature\Controllers\Range\Item\Destroy;

use App\Models\Range\Item;
use App\Models\Range\Specification;
use App\Models\Range\SpecificationItem;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReplacementComponentStoreControllerTest extends TestCase
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
    public function a_deleted_item_can_be_replaced_in_all_related_specifications()
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

        foreach ($assemblyItems as $assemblyItem) {
            SpecificationItem::factory()->create([
                'specification_number' => $assemblyItem->specification_number,
                'item_id' => $detailItem->id
            ]);
        }

        $newDetailItem = Item::factory()->create([
            'drawing' => 'Заменяющая Деталь',
            'name' => 'Заменяющая Деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Межцеховой склад 2 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        $replacementData = [
            'drawing' => $newDetailItem->drawing,
            'factor' => 2
        ];

        $response = $this->post("/items/{$detailItem->id}/confirm-component-replacement", $replacementData);
        $detailItem->refresh();

        $this->assertSoftDeleted('items', ['id' => $detailItem->id]);

        foreach ($assemblyItems as $assemblyItem) {
            $specification = Specification::withoutGlobalScope('valid')
                ->where('number', $assemblyItem->specification_number)
                ->where('valid_to', date('Y-m-d'))->first();

            $this->assertDatabaseHas('specifications', [
                'number' => $specification->number . '-' . '1'
            ]);

            $newSpecification = Specification::find($specification->number . '-' . '1');
            $this->assertEquals($specification->valid_to, $newSpecification->valid_from);

            $this->assertDatabaseHas('item_specification', [
                'item_id' => $assemblyItem->id,
                'specification_number' => $newSpecification->number
            ]);

            $this->assertDatabaseHas('specification_item', [
                'specification_number' => $newSpecification->number,
                'item_id' => $newDetailItem->id,
                'cnt' => DB::table('specification_item')
                        ->where('specification_number', $assemblyItem->specification_number)
                        ->where('item_id', $detailItem->id)->first()->cnt * $replacementData['factor']
            ]);
        }

        $response->assertRedirect('/items');

        $response = $this->get('/items');
        $response->assertSee(__('messages.successful_delete'));
    }
}
