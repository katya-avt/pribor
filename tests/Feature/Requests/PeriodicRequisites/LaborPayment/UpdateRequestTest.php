<?php

namespace Tests\Feature\Requests\PeriodicRequisites\LaborPayment;

use App\Models\Range\Point;
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
        return [
            'request_should_fail_when_base_payment_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'base_payment' => null
                ]
            ],

            'request_should_fail_when_base_payment_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'base_payment' => 'Не число.'
                ]
            ],

            'request_should_fail_when_base_payment_less_than_0' => [
                'passed' => false,
                'data' => [
                    'base_payment' => -5
                ]
            ],

            'request_should_fail_when_base_payment_greater_than_99' => [
                'passed' => false,
                'data' => [
                    'base_payment' => 100
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

        $from = $this->from("/labor-payment/{$point->code}/edit");
        $response = $from->patch("/labor-payment/{$point->code}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/labor-payment');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
