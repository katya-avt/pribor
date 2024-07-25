<?php

namespace Tests\Feature\Controllers\Range\PurchasedItems;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
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
    public function a_purchased_item_attributes_can_be_updated()
    {
        $item = Item::has('purchasedItem')->first();

        $newData = [
            'purchase_lot' => 20.15,
            'order_point' => 15.58
        ];

        $response = $this->patch("/purchased-items/{$item->id}", $newData);
        $item->refresh();

        $this->assertDatabaseHas('purchased_items', ['item_id' => $item->id] + $newData);

        $response->assertRedirect('/purchased-items');

        $response = $this->get('/purchased-items');
        $response->assertSee(__('messages.successful_update'));
    }
}
