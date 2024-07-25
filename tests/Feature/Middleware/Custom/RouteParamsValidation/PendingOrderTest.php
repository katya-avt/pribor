<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PendingOrderTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function pending_order_can_be_put_into_production()
    {
        $pendingOrder = Order::where('status_id', OrderStatus::PENDING)->first();

        $response = $this->patch("/orders/{$pendingOrder->id}/put-into-production");
        $response->assertRedirect();
    }

    /** @test */
    public function not_pending_order_cannot_be_put_into_production()
    {
        $inProductionOrder = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        $response = $this->patch("/orders/{$inProductionOrder->id}/put-into-production");
        $response->assertNotFound();
    }
}
