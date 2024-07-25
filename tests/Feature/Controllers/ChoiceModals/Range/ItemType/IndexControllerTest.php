<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\ItemType;

use App\Models\Range\ItemType;
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
    public function response_for_route_item_type_choice_is_view_choice_modals_range_item_type_index_with_item_types()
    {
        $itemTypes = ItemType::all();

        $response = $this->get("/item-type-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.item-type.index');

        foreach ($itemTypes as $itemType) {
            $response->assertSee($itemType->name);
        }
    }
}
