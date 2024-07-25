<?php

namespace Tests\Feature\Controllers\Orders\Order;

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
    public function response_for_route_orders_create_is_view_orders_orders_create_with_store_form()
    {
        $response = $this->get('/orders/create');
        $response->assertOk();

        $response->assertViewIs('orders.orders.create');
    }
}
