<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\Item;

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
    public function response_for_route_item_choice_is_view_choice_modals_range_item_index_with_items()
    {
        $items = Item::take(10)->get();

        $response = $this->get("/item-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.item.index');

        foreach ($items as $item) {
            $response->assertSee($item->drawing);
            $response->assertSee($item->name);
            $response->assertSee($item->group->name);
            $response->assertSee($item->itemType->name);
            $response->assertSee($item->unit->short_name);
            $response->assertSee($item->manufactureType->name);
        }
    }
}
