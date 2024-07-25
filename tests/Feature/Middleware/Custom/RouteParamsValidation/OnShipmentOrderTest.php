<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OnShipmentOrderTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function on_shipment_order_can_be_shipped()
    {
        $onShipmentOrder = Order::where('status_id', OrderStatus::ON_SHIPMENT)->first();

        $response = $this->patch("/orders/{$onShipmentOrder->id}/ship");
        $response->assertRedirect();
    }

    /** @test */
    public function not_on_shipment_order_cannot_be_shipped()
    {
        $inProductionOrder = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        $response = $this->patch("/orders/{$inProductionOrder->id}/ship");
        $response->assertNotFound();
    }
}
