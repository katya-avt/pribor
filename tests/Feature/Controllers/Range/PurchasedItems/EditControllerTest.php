<?php

namespace Tests\Feature\Controllers\Range\PurchasedItems;

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
    public function response_for_route_purchased_items_edit_is_view_range_purchased_items_edit_with_update_form()
    {
        $item = Item::has('purchasedItem')->first();

        $response = $this->get("/purchased-items/{$item->id}/edit");
        $response->assertOk();

        $response->assertViewIs('range.purchased-items.edit');

        $response->assertSee($item->drawing);
        $response->assertSee($item->name);
        $response->assertSee($item->purchasedItem->purchase_lot);
        $response->assertSee($item->purchasedItem->order_point);
    }
}
