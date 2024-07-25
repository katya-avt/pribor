<?php

namespace Tests\Feature\Requests\PeriodicRequisites\LaborPayment;

use App\Models\Range\Point;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowRequestTest extends TestCase
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
            'request_should_fail_when_period_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'period' => 'Значение не из списка.'
                ]
            ],

            'request_should_fail_when_quarter_is_not_a_date' => [
                'passed' => false,
                'data' => [
                    'quarter' => 'Не дата.'
                ]
            ],

            'request_should_fail_when_month_is_not_a_date' => [
                'passed' => false,
                'data' => [
                    'month' => 'Не дата.'
                ]
            ],

            'request_should_fail_when_date_is_not_a_date' => [
                'passed' => false,
                'data' => [
                    'date' => 'Не дата.'
                ]
            ],

            'request_should_fail_when_from_date_is_not_a_date' => [
                'passed' => false,
                'data' => [
                    'from_date' => 'Не дата.'
                ]
            ],

            'request_should_fail_when_from_date_is_provided_without_to_date' => [
                'passed' => false,
                'data' => [
                    'from_date' => '2024-01-01'
                ]
            ],

            'request_should_fail_when_to_date_is_not_a_date' => [
                'passed' => false,
                'data' => [
                    'to_date' => 'Не дата.'
                ]
            ],

            'request_should_fail_when_to_date_is_provided_without_from_date' => [
                'passed' => false,
                'data' => [
                    'to_date' => '2024-01-01'
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
        $point = Point::first();

        $from = $this->from("/labor-payment/{$point->code}");

        $response = $from->get(route('periodic-requisites.labor-payment.show',
            ['point' => $point->code] + $mockedRequestData));

        if ($shouldPass) {
            $response->assertOk();
            $response->assertSessionDoesntHaveErrors();
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
