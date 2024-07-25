<?php

namespace Tests\Feature\Controllers\Range\Item\Destroy;

use App\Models\Range\Cover;
use App\Models\Range\CoverItem;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ReplacementCoverStoreControllerTest extends TestCase
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
    public function a_deleted_item_can_be_replaced_in_all_related_covers()
    {
        $assemblyItems = collect();

        $paintItem = Item::factory()->create([
            'drawing' => 'Лакокрасочное покрытие',
            'name' => 'Лакокрасочное покрытие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Лакокрасочные покрытия',
            'unit_code' => 'л',
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
            CoverItem::factory()->create([
                'cover_number' => $assemblyItem->cover_number,
                'item_id' => $paintItem->id
            ]);
        }

        $newPaintItem = Item::factory()->create([
            'drawing' => 'Заменяющее Лакокрасочное покрытие',
            'name' => 'Заменяющее Лакокрасочное покрытие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Лакокрасочные покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Межцеховой склад 2 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        $replacementData = [
            'drawing' => $newPaintItem->drawing,
            'factor' => 2.5
        ];

        $response = $this->post("/items/{$paintItem->id}/confirm-cover-replacement", $replacementData);
        $paintItem->refresh();

        $this->assertSoftDeleted('items', ['id' => $paintItem->id]);

        foreach ($assemblyItems as $assemblyItem) {
            $cover = Cover::withoutGlobalScope('valid')
                ->where('number', $assemblyItem->cover_number)
                ->where('valid_to', date('Y-m-d'))->first();

            $this->assertDatabaseHas('covers', [
                'number' => $cover->number . '-' . '1'
            ]);

            $newCover = Cover::find($cover->number . '-' . '1');
            $this->assertEquals($cover->valid_to, $newCover->valid_from);

            $this->assertDatabaseHas('item_cover', [
                'item_id' => $assemblyItem->id,
                'cover_number' => $newCover->number
            ]);

            $newConsumption = DB::table('cover_item')
                    ->where('cover_number', $assemblyItem->cover_number)
                    ->where('item_id', $paintItem->id)->first()->consumption * $replacementData['factor'];

            $this->assertDatabaseHas('cover_item', [
                'cover_number' => $newCover->number,
                'item_id' => $newPaintItem->id,
                'consumption' => round($newConsumption, 5)
            ]);
        }

        $response->assertRedirect('/items');

        $response = $this->get('/items');
        $response->assertSee(__('messages.successful_delete'));
    }
}
