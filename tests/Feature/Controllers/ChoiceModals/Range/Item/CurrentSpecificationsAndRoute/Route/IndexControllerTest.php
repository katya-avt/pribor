<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\Item\CurrentSpecificationsAndRoute\Route;

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
    public function response_for_route_current_item_route_choice_is_view_choice_modals_range_item_current_specifications_and_route_route_index_with_item_routes()
    {
        $item = Item::has('routes')->first();

        $response = $this->get("/current-item-route-choice/{$item->id}");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.item.current-specifications-and-route.route.index');

        foreach ($item->routes as $itemRoute) {
            $response->assertSee($itemRoute->number);
        }
    }
}
