<?php

namespace Tests\Feature\Controllers\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SendOnShipmentControllerTest extends TestCase
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
    public function a_complete_production_order_can_be_sent_on_shipment()
    {
        $order = Order::where('status_id', OrderStatus::PRODUCTION_COMPLETED)->first();

        $response = $this->patch("/orders/{$order->id}/send-on-shipment");
        $order->refresh();

        $this->assertEquals(OrderStatus::ON_SHIPMENT, $order->status_id);

        $response->assertRedirect("/orders/status/" . OrderStatus::ON_SHIPMENT_URL_PARAM_NAME);

        $response = $this->get("orders/status/" . OrderStatus::ON_SHIPMENT_URL_PARAM_NAME);
        $response->assertSee(__('messages.successful_send_on_shipment'));
    }
}
