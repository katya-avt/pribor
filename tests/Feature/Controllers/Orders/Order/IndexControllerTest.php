<?php

namespace Tests\Feature\Controllers\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    public function orderStatusProvider()
    {
        return [
            'response_for_route_orders_index_is_view_orders_orders_index_with_pending_orders' => [
                'role_name' => 'Сотрудник экономического отдела',
                'order_status_name' => 'Отложен'
            ],

            'response_for_route_orders_index_is_view_orders_orders_index_with_in_production_orders' => [
                'role_name' => 'Сотрудник КТО',
                'order_status_name' => 'В производстве'
            ],

            'response_for_route_orders_index_is_view_orders_orders_index_with_production_completed_orders' => [
                'role_name' => 'Сотрудник отдела снабжения и сбыта',
                'order_status_name' => 'Произведен'
            ],

            'response_for_route_orders_index_is_view_orders_orders_index_with_on_shipment_orders' => [
                'role_name' => 'Сотрудник отдела снабжения и сбыта',
                'order_status_name' => 'На отгрузке'
            ],

            'response_for_route_orders_index_is_view_orders_orders_index_with_shipped_orders' => [
                'role_name' => 'Сотрудник экономического отдела',
                'order_status_name' => 'Отгружен'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider orderStatusProvider
     * @param string $roleName
     * @param string $orderStatusName
     */
    public function order_viewing_as_expected($roleName, $orderStatusName)
    {
        $user = User::whereHas('role', function ($query) use ($roleName) {
            $query->where('roles.name', $roleName);
        })->first();
        Sanctum::actingAs($user);

        $orderStatus = OrderStatus::where('name', $orderStatusName)->first();

        $orders = Order::where('status_id', $orderStatus->id)->take(10)->get();

        $response = $this->get("orders/status/" . $orderStatus->url_param_name);
        $response->assertOk();

        $response->assertViewIs('orders.orders.index');

        foreach ($orders as $order) {
            $response->assertSee($order->code);
            $response->assertSee($order->name);
            $response->assertSee($order->creation_date);
            $response->assertSee($order->customer->name);
        }
    }
}
