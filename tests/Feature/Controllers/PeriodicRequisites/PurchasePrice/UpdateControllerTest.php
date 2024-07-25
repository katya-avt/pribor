<?php

namespace Tests\Feature\Controllers\PeriodicRequisites\PurchasePrice;

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

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function a_purchased_item_purchase_price_can_be_updated()
    {
        $item = Item::has('purchasedItem')->first();

        $newData = [
            'purchase_price' => 1.25
        ];

        $response = $this->patch("/purchase-price/{$item->id}", $newData);
        $item->refresh();

        $this->assertDatabaseHas('purchased_items', ['item_id' => $item->id] + $newData);

        $response->assertRedirect('/purchase-price');

        $response = $this->get('/purchase-price');
        $response->assertSee(__('messages.successful_update'));
    }
}
