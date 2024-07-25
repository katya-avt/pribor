<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompleteProductionOrderTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function complete_production_order_can_be_sent_on_shipment()
    {
        $completeProductionOrder = Order::where('status_id', OrderStatus::PRODUCTION_COMPLETED)->first();

        $response = $this->patch("/orders/{$completeProductionOrder->id}/send-on-shipment");
        $response->assertRedirect();
    }

    /** @test */
    public function not_complete_production_order_cannot_be_sent_on_shipment()
    {
        $inProductionOrder = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        $response = $this->patch("/orders/{$inProductionOrder->id}/send-on-shipment");
        $response->assertNotFound();
    }
}
