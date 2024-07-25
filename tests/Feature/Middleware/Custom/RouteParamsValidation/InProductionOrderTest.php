<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class InProductionOrderTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function in_production_order_can_completed_its_production()
    {
        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);

        $inProductionOrder = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        $response = $this->patch("/orders/{$inProductionOrder->id}/complete-production");
        $response->assertRedirect();
    }

    /** @test */
    public function not_in_production_order_cannot_completed_its_production()
    {
        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);

        $pendingOrder = Order::where('status_id', OrderStatus::PENDING)->first();

        $response = $this->patch("/orders/{$pendingOrder->id}/complete-production");
        $response->assertNotFound();
    }
}
