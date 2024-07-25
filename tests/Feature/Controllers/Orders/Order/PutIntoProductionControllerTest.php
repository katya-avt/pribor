<?php

namespace Tests\Feature\Controllers\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PutIntoProductionControllerTest extends TestCase
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
    public function a_pending_order_can_be_put_into_production()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $response = $this->patch("/orders/{$order->id}/put-into-production");
        $order->refresh();

        $this->assertEquals(OrderStatus::IN_PRODUCTION, $order->status_id);
        $this->assertNotNull($order->launch_date);

        $response->assertRedirect("/orders/status/" . OrderStatus::IN_PRODUCTION_URL_PARAM_NAME);

        $response = $this->get("orders/status/" . OrderStatus::IN_PRODUCTION_URL_PARAM_NAME);
        $response->assertSee(__('messages.successful_put_into_production'));
    }
}
