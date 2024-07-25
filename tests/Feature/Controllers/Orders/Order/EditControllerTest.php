<?php

namespace Tests\Feature\Controllers\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditControllerTest extends TestCase
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
    public function response_for_orders_edit_is_view_orders_orders_edit_with_update_form()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $response = $this->get("/orders/{$order->id}/edit");
        $response->assertOk();

        $response->assertViewIs('orders.orders.edit');

        $response->assertSee($order->code);
        $response->assertSee($order->name);

        $closingDate = Carbon::createFromFormat('d.m.Y', $order->closing_date)->format('Y-m-d');
        $response->assertSee($closingDate);

        $response->assertSee($order->customer->name);
        $response->assertSee($order->note);
    }
}
