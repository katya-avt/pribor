<?php

namespace Tests\Feature\Controllers\ItemAvailabilityAndConsumption\Availability;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditControllerTest extends TestCase
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
    public function response_for_availability_edit_is_view_item_availability_and_consumption_availability_edit_with_update_form()
    {
        $item = Item::all()->random();

        $response = $this->get("/availability/{$item->id}/edit");
        $response->assertOk();

        $response->assertViewIs('item-availability-and-consumption.availability.edit');

        $response->assertSee($item->drawing);
        $response->assertSee($item->name);
        $response->assertSee($item->cnt);
    }
}
