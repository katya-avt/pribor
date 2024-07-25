<?php

namespace Tests\Feature\Controllers\Admin\MarkedForDeletion\Item;

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

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_admin_marked_for_deletion_items_index_is_view_admin_marked_for_deletion_items_index_with_marked_for_deletion_items()
    {
        $items = Item::onlyTrashed()->take(10)->get();

        $response = $this->get("/admin/marked-for-deletion/items");
        $response->assertOk();

        $response->assertViewIs('admin.marked-for-deletion.items.index');

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
