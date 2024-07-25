<?php

namespace Tests\Feature\Requests\Orders\Order;

use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexRequestTest extends TestCase
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
            'request_should_fail_when_customer_inn_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'customer_inn' => 'Значение не из списка.'
                ]
            ],

            'request_should_fail_when_drawing_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'drawing' => 'Значение не из списка.'
                ]
            ],

            'request_should_fail_when_sort_by_is_provided_without_sort_direction' => [
                'passed' => false,
                'data' => [
                    'sort_by' => 'Дата создания'
                ]
            ],

            'request_should_fail_when_sort_by_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'sort_by' => 'Значение не из списка.'
                ]
            ],

            'request_should_fail_when_sort_direction_is_provided_without_sort_by' => [
                'passed' => false,
                'data' => [
                    'sort_direction' => 'Дата создания'
                ]
            ],

            'request_should_fail_when_sort_direction_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'sort_direction' => 'Значение не из списка.'
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
        $from = $this->from("/orders/status/" . OrderStatus::IN_PRODUCTION_URL_PARAM_NAME);

        $response = $from->get(route('orders.index',
            ['orderStatus' => OrderStatus::IN_PRODUCTION_URL_PARAM_NAME] + $mockedRequestData));

        if ($shouldPass) {
            $response->assertOk();
            $response->assertSessionDoesntHaveErrors();
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
