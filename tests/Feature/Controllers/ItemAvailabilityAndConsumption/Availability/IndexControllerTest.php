<?php

namespace Tests\Feature\Controllers\ItemAvailabilityAndConsumption\Availability;

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

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_availability_index_is_view_item_availability_and_consumption_availability_index_with_items_and_its_cnt()
    {
        $items = Item::take(10)->get();

        $response = $this->get("/availability");
        $response->assertOk();

        $response->assertViewIs('item-availability-and-consumption.availability.index');

        foreach ($items as $item) {
            $response->assertSee($item->drawing);
            $response->assertSee($item->name);
            $response->assertSee($item->cnt);
        }
    }
}
