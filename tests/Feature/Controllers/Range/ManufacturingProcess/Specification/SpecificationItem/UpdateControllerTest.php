<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
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
    public function a_specification_item_can_be_updated()
    {
        $item = Item::has('detail')->whereHas('currentSpecification', function ($query) {
            $query->has('items', '=', 1)->whereNull('added_to_order_at');
        })->first();

        $specification = Specification::find($item->specification_number);
        $specificationItem = $specification->items()->first();

        $newSpecificationItem = Item::whereHas('group', function ($query) {
            $query->where('groups.name', Group::METAL);
        })->where('id', '<>', $specificationItem->id)->first();

        $newData = [
            'drawing' => $newSpecificationItem->drawing,
            'cnt' => 2
        ];

        $response = $this->patch("/specifications/{$specification->number}/{$specificationItem->id}", $newData);

        $updatedRecord = DB::table('specification_item')
            ->where('specification_number', $specification->number)
            ->where('item_id', $newSpecificationItem->id)
            ->first();

        $this->assertNotNull($updatedRecord);
        $this->assertEquals($newData['cnt'], $updatedRecord->cnt);

        $response->assertRedirect("/specifications/{$specification->number}");

        $response = $this->get("/specifications/{$specification->number}");
        $response->assertSee(__('messages.successful_update'));
    }
}
