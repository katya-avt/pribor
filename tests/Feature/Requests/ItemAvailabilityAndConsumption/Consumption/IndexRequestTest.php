<?php

namespace Tests\Feature\Requests\ItemAvailabilityAndConsumption\Consumption;

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

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
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
        $from = $this->from('/consumption');

        $response = $from->get(route('item-availability-and-consumption.consumption.index', $mockedRequestData));

        if ($shouldPass) {
            $response->assertOk();
            $response->assertSessionDoesntHaveErrors();
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
