<?php

namespace Tests\Feature\Controllers\Orders\Order;

use App\Models\Orders\Customer;
use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
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
    public function a_pending_order_can_be_updated()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $newData = [
            'code' => '0-0',
            'name' => '0А',
            'closing_date' => '2024-07-01',
            'customer_inn' => 'АВТ_П3_17',
            'note' => 'Примечание к заказу'
        ];

        $response = $this->patch("/orders/{$order->id}", $newData);

        $newOrderData = $newData;
        $newOrderData['customer_inn'] = Customer::getCustomerInnByCustomerName($newData['customer_inn']);

        $this->assertDatabaseHas('orders', ['id' => $order->id] + $newOrderData);

        $response->assertRedirect("/orders/status/" . OrderStatus::PENDING_URL_PARAM_NAME);

        $response = $this->get("orders/status/" . OrderStatus::PENDING_URL_PARAM_NAME);
        $response->assertSee(__('messages.successful_update'));
    }
}
