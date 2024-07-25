<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Models\Range\Item;
use App\Models\Range\Specification;
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
    public function a_specification_item_can_be_stored()
    {
        $specification = Specification::create([
            'number' => '10000000'
        ]);

        $detailItem = Item::factory()->create([
            'drawing' => 'Деталь',
            'name' => 'Деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Межцеховой склад 2 цеха',
            'manufacture_type_id' => 'Страховой запас'
        ]);

        $data = [
            'drawing' => $detailItem->drawing,
            'cnt' => 2
        ];

        $response = $this->post("/specifications/{$specification->number}", $data);

        $storedRecord = DB::table('specification_item')
            ->where('specification_number', $specification->number)
            ->where('item_id', $detailItem->id)
            ->first();

        $this->assertNotNull($storedRecord);
        $this->assertEquals($data['cnt'], $storedRecord->cnt);

        $response->assertRedirect("/specifications/{$specification->number}");

        $response = $this->get("/specifications/{$specification->number}");
        $response->assertSee(__('messages.successful_store'));
    }
}
