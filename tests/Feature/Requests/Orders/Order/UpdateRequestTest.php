<?php

namespace Tests\Feature\Requests\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return (new StoreRequestTest())->validationProvider();
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $from = $this->from("/orders/{$order->id}/edit");

        $response = $from->patch("/orders/{$order->id}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect("/orders/status/" . OrderStatus::PENDING_URL_PARAM_NAME);
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_code_is_not_unique()
    {
        $firstOrder = Order::where('status_id', OrderStatus::PENDING)->first();
        $secondOrder = Order::where('status_id', OrderStatus::PENDING)
            ->where('id', '<>', $firstOrder->id)->first();

        $data = [
            'code' => $secondOrder->code,
            'name' => 'Наименование',
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null
        ];

        $from = $this->from("/orders/{$firstOrder->id}/edit");

        $response = $from->patch("/orders/{$firstOrder->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_name_is_not_unique()
    {
        $firstOrder = Order::where('status_id', OrderStatus::PENDING)->first();
        $secondOrder = Order::where('status_id', OrderStatus::PENDING)
            ->where('id', '<>', $firstOrder->id)->first();

        $data = [
            'code' => 'Код',
            'name' => $secondOrder->name,
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null
        ];

        $from = $this->from("/orders/{$firstOrder->id}/edit");

        $response = $from->patch("/orders/{$firstOrder->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_code_is_not_updated()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $data = [
            'code' => $order->code,
            'name' => 'Наименование',
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null
        ];

        $from = $this->from("/orders/{$order->id}/edit");

        $response = $from->patch("/orders/{$order->id}", $data);
        $response->assertRedirect("/orders/status/" . OrderStatus::PENDING_URL_PARAM_NAME);
    }

    /** @test */
    public function request_should_not_fail_when_unique_name_is_not_updated()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $data = [
            'code' => 'Код',
            'name' => $order->name,
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null
        ];

        $from = $this->from("/orders/{$order->id}/edit");

        $response = $from->patch("/orders/{$order->id}", $data);
        $response->assertRedirect("/orders/status/" . OrderStatus::PENDING_URL_PARAM_NAME);
    }
}
