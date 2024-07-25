<?php

namespace Tests\Feature\Requests\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreRequestTest extends TestCase
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
        return [
            'request_should_fail_when_code_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'code' => null,
                    'name' => 'Наименование',
                    'closing_date' => '2024-12-12',
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_code_has_less_than_2_characters' => [
                'passed' => false,
                'data' => [
                    'code' => 'К',
                    'name' => 'Наименование',
                    'closing_date' => '2024-12-12',
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_code_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'code' => str_repeat('К', 256),
                    'name' => 'Наименование',
                    'closing_date' => '2024-12-12',
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_name_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'code' => 'Код',
                    'name' => null,
                    'closing_date' => '2024-12-12',
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_name_has_less_than_2_characters' => [
                'passed' => false,
                'data' => [
                    'code' => 'Код',
                    'name' => 'Н',
                    'closing_date' => '2024-12-12',
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_name_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'code' => 'Код',
                    'name' => str_repeat('Н', 256),
                    'closing_date' => '2024-12-12',
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_closing_date_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'code' => 'Код',
                    'name' => 'Наименование',
                    'closing_date' => null,
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_closing_date_is_not_date' => [
                'passed' => false,
                'data' => [
                    'code' => 'Код',
                    'name' => 'Наименование',
                    'closing_date' => 'Не дата.',
                    'customer_inn' => 'АВТ_П3_17',
                    'note' => null,
                ]
            ],

            'request_should_fail_when_customer_inn_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'code' => 'Код',
                    'name' => 'Наименование',
                    'closing_date' => '2024-12-12',
                    'customer_inn' => null,
                    'note' => null,
                ]
            ],

            'request_should_fail_when_customer_inn_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'code' => 'Код',
                    'name' => 'Наименование',
                    'closing_date' => '2024-12-12',
                    'customer_inn' => 'Значение не из списка.',
                    'note' => null,
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $from = $this->from("/orders/create");

        $response = $from->post("/orders", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect("/orders");
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_code_is_not_unique()
    {
        $order = Order::first();

        $data = [
            'code' => $order->code,
            'name' => 'Наименование',
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null
        ];

        $from = $this->from("/orders/create");

        $response = $from->post("/orders", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_name_is_not_unique()
    {
        $order = Order::first();

        $data = [
            'code' => 'Код',
            'name' => $order->name,
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null
        ];

        $from = $this->from("/orders/create");

        $response = $from->post("/orders", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
