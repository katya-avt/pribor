<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
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

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_point_code_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'point_code' => null,
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_point_code_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'Значение не из списка.',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_operation_code_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => null,
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_operation_code_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => 'Значение не из списка.',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_unit_time_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => null,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_unit_time_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 'Не число.',
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_unit_time_less_than_0' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => -6.82,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_unit_time_greater_than_9999' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 100000,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_working_time_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => null,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_working_time_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 'Не число.',
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_working_time_less_than_0' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => -15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_working_time_greater_than_9999' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 100000,
                    'lead_time' => 4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_lead_time_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => null,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_lead_time_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 'Не число.',
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_lead_time_less_than_0' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => -4.18,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_lead_time_greater_than_9999' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 100000,
                    'rate_code' => 'С1'
                ]
            ],

            'request_should_fail_when_rate_code_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => null
                ]
            ],

            'request_should_fail_when_rate_code_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'point_code' => 'ЛазРез',
                    'operation_code' => '0101',
                    'unit_time' => 6.82,
                    'working_time' => 15.65,
                    'lead_time' => 4.18,
                    'rate_code' => 'Значение не из списка.'
                ]
            ],
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     */
    public function validation_results_as_expected($shouldPass)
    {
        $route = Route::whereNull('added_to_order_at')->first();

        $from = $this->from("/routes/{$route->number}/create");

        $response = $from->post("/routes/{$route->number}");

        if ($shouldPass) {
            $response->assertRedirect("/routes/{$route->number}");
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
