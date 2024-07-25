<?php

namespace Tests\Feature\Controllers\Range\Item\ManufacturingProcess\Specification;

use App\Models\Range\Item;
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
    public function response_for_route_items_specifications_index_is_view_range_items_manufacturing_process_specifications_index_with_item_specifications()
    {
        $item = Item::has('specifications')->first();

        $response = $this->get("/items/{$item->id}/specifications");
        $response->assertOk();

        $response->assertViewIs('range.items.manufacturing-process.specifications.index');

        foreach ($item->specifications as $itemSpecification) {
            $response->assertSee($itemSpecification->number);
        }
    }
}
