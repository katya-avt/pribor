<?php

namespace Tests\Feature\Controllers\Range\Item;

use App\Models\Range\Group;
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
    public function response_for_route_items_edit_is_view_range_items_edit_with_update_form()
    {
        $item = Item::whereNull('added_to_order_at')->first();

        $response = $this->get("/items/{$item->id}/edit");
        $response->assertOk();

        $response->assertViewIs('range.items.edit');
    }

    /** @test */
    public function update_form_for_detail_is_correct()
    {
        $item = Item::has('detail')->whereNull('added_to_order_at')->first();

        $response = $this->get("/items/{$item->id}/edit");
        $response->assertOk();

        $response->assertSee($item->drawing);
        $response->assertSee($item->unit->short_name);

        $response->assertSee($item->detail->detail_size);
        $response->assertSee($item->detail->billet_size);

        $response->assertSee($item->name);
        $response->assertSee($item->group->name);
        $response->assertSee($item->mainWarehouse->name);
        $response->assertSee($item->itemType->name);
        $response->assertSee($item->manufactureType->name);
    }

    /** @test */
    public function update_form_for_assembly_item_is_correct()
    {
        $item = Item::whereHas('group', function ($query) {
            $query->where('groups.name', Group::ASSEMBLY_ITEM);
        })->whereNull('added_to_order_at')->first();

        $response = $this->get("/items/{$item->id}/edit");
        $response->assertOk();

        $response->assertSee($item->drawing);
        $response->assertSee($item->unit->short_name);

        $response->assertSee($item->name);
        $response->assertSee($item->group->name);
        $response->assertSee($item->mainWarehouse->name);
        $response->assertSee($item->itemType->name);
        $response->assertSee($item->manufactureType->name);
    }

    /** @test */
    public function update_form_for_purchased_item_is_correct()
    {
        $item = Item::has('purchasedItem')->whereNull('added_to_order_at')->first();

        $response = $this->get("/items/{$item->id}/edit");
        $response->assertOk();

        $response->assertSee($item->drawing);
        $response->assertSee($item->unit->short_name);

        $response->assertSee($item->name);
        $response->assertSee($item->group->name);
        $response->assertSee($item->mainWarehouse->name);
        $response->assertSee($item->itemType->name);
        $response->assertSee($item->manufactureType->name);

        $response->assertSee($item->purchasedItem->purchase_price);
        $response->assertSee($item->purchasedItem->purchase_lot);
        $response->assertSee($item->purchasedItem->order_point);
        $response->assertSee($item->purchasedItem->unit_factor);
        $response->assertSee($item->purchasedItem->unit->short_name);
    }
}
