<?php

namespace Tests\Feature\Controllers\Range\Item\CurrentSpecificationsAndRoute;

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

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_items_current_specifications_and_route_edit_is_view_range_items_current_specifications_and_route_edit_with_update_form()
    {
        $item = Item::whereNotNull('specification_number')
            ->whereNotNull('cover_number')
            ->whereNotNull('route_number')
            ->first();

        $response = $this->get("/items/{$item->id}/current-specifications-and-route/edit");
        $response->assertOk();

        $response->assertViewIs('range.items.current-specifications-and-route.edit');

        $response->assertSee($item->specification_number);
        $response->assertSee($item->cover_number);
        $response->assertSee($item->route_number);
    }
}
