<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Models\Range\Cover;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreControllerTest extends TestCase
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
    public function a_cover_item_can_be_stored()
    {
        $cover = Cover::create([
            'number' => '10000000'
        ]);

        $paintItem = Item::factory()->create([
            'drawing' => 'Лакокрасочное покрытие',
            'name' => 'Лакокрасочное покрытие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Лакокрасочные покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Межцеховой склад 2 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        $data = [
            'drawing' => $paintItem->drawing,
            'area' => 151.86,
            'consumption' => 0.00007
        ];

        $response = $this->post("/covers/{$cover->number}", $data);

        $storedRecord = DB::table('cover_item')
            ->where('cover_number', $cover->number)
            ->where('item_id', $paintItem->id)
            ->first();

        $this->assertNotNull($storedRecord);
        $this->assertEquals($data['area'], $storedRecord->area);
        $this->assertEquals($data['consumption'], $storedRecord->consumption);

        $response->assertRedirect("/covers/{$cover->number}");

        $response = $this->get("/covers/{$cover->number}");
        $response->assertSee(__('messages.successful_store'));
    }
}
