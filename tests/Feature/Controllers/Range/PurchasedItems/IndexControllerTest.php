<?php

namespace Tests\Feature\Controllers\Range\PurchasedItems;

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
    public function response_for_route_purchased_items_index_is_view_range_purchased_items_index_with_purchased_items()
    {
        $items = Item::has('purchasedItem')->take(10)->get();

        $response = $this->get('/purchased-items');
        $response->assertOk();

        $response->assertViewIs('range.purchased-items.index');

        foreach ($items as $item) {
            $response->assertSee($item->drawing);
            $response->assertSee($item->name);
            $response->assertSee($item->purchasedItem->purchase_price);
            $response->assertSee($item->purchasedItem->purchase_lot);
            $response->assertSee($item->purchasedItem->order_point);
        }
    }
}
