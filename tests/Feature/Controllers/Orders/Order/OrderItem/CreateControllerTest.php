<?php

namespace Tests\Feature\Controllers\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateControllerTest extends TestCase
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
    public function response_for_route_orders_order_items_create_is_view_orders_orders_order_items_create_with_store_form()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $response = $this->get("/orders/{$order->id}/create");
        $response->assertOk();

        $response->assertViewIs('orders.orders.order-items.create');
    }
}
