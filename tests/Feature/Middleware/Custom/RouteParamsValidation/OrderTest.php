<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Range\Specification;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function order_is_available_if_it_has_been_selected_from_list()
    {
        $order = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        $response = $this->get("/orders/{$order->id}");
        $response->assertOk();
    }

    /** @test */
    public function order_is_not_available_if_it_has_not_been_selected_from_list()
    {
        $specification = Specification::first();

        $response = $this->get("/orders/{$specification->number}");
        $response->assertNotFound();
    }
}
