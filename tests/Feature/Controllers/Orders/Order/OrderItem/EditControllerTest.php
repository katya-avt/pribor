<?php

namespace Tests\Feature\Controllers\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
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

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_orders_order_items_edit_is_view_orders_orders_order_items_edit_with_update_form()
    {
        $order = Order::has('items')->where('status_id', OrderStatus::PENDING)->first();
        $orderItem = $order->items->first();

        $response = $this->get("/orders/{$order->id}/{$orderItem->id}/edit");
        $response->assertOk();

        $response->assertViewIs('orders.orders.order-items.edit');

        $response->assertSee($orderItem->drawing);
        $response->assertSee($orderItem->pivot->per_unit_price);
        $response->assertSee($orderItem->pivot->cnt);
    }
}
