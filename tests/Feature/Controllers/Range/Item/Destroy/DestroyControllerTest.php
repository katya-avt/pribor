<?php

namespace Tests\Feature\Controllers\Range\Item\Destroy;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyControllerTest extends TestCase
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
    public function response_for_route_items_destroy_is_redirect_to_range_items_confirm_component_replacement_with_store_form_when_related_specifications_collect_is_not_empty()
    {
        $item = Item::has('relatedSpecifications')->first();

        $response = $this->delete("/items/{$item->id}");

        $response->assertRedirect("/items/{$item->id}/confirm-component-replacement");
    }

    /** @test */
    public function response_for_route_items_destroy_is_redirect_to_range_items_confirm_cover_replacement_with_store_form_when_related_covers_collect_is_not_empty()
    {
        $item = Item::has('relatedCovers')->first();

        $response = $this->delete("/items/{$item->id}");

        $response->assertRedirect("/items/{$item->id}/confirm-cover-replacement");
    }

    /** @test */
    public function an_item_can_be_deleted_when_it_has_not_related_specifications_or_covers()
    {
        $item = Item::doesntHave('relatedSpecifications')->doesntHave('relatedCovers')->first();

        $response = $this->delete("/items/{$item->id}");

        $this->assertSoftDeleted('items', ['id' => $item->id]);

        $response->assertRedirect("/items");
    }
}
