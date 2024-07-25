<?php

namespace Tests\Feature\Controllers\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShipControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function an_on_shipment_order_can_be_shipped()
    {
        $order = Order::where('status_id', OrderStatus::ON_SHIPMENT)->first();

        $response = $this->patch("/orders/{$order->id}/ship");
        $order->refresh();

        $this->assertEquals(OrderStatus::SHIPPED, $order->status_id);
        $this->assertNotNull($order->completion_date);

        $response->assertRedirect("/orders/status/" . OrderStatus::ON_SHIPMENT_URL_PARAM_NAME);

        $response = $this->get("orders/status/" . OrderStatus::ON_SHIPMENT_URL_PARAM_NAME);
        $response->assertSee(__('messages.successful_ship'));
    }
}
