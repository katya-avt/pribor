<?php

namespace Tests\Feature\Controllers\Range\Item\Destroy;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ConfirmComponentReplacementControllerTest extends TestCase
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
    public function response_for_route_items_confirm_component_replacement_is_view_range_items_destroy_confirm_component_replacement_with_store_form()
    {
        $item = Item::has('relatedSpecifications')->first();

        $response = $this->get("/items/{$item->id}/confirm-component-replacement");
        $response->assertOk();

        $response->assertViewIs('range.items.destroy.confirm-component-replacement');

        $response->assertSee($item->drawing);

        foreach ($item->relatedSpecifications as $relatedSpecification) {
            $response->assertSee($relatedSpecification->number);
        }
    }
}
