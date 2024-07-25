<?php

namespace Tests\Feature\Requests\ItemAvailabilityAndConsumption\Availability;

use App\Models\Range\Item;
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

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_cnt_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'cnt' => null
                ]
            ],

            'request_should_fail_when_cnt_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'cnt' => 'Не число.'
                ]
            ],

            'request_should_fail_when_cnt_less_than_0' => [
                'passed' => false,
                'data' => [
                    'cnt' => -5
                ]
            ],

            'request_should_fail_when_cnt_greater_than_999999' => [
                'passed' => false,
                'data' => [
                    'cnt' => 1000000
                ]
            ],

            'request_should_fail_when_cnt_is_not_integer_for_unit_item' => [
                'passed' => false,
                'data' => [
                    'cnt' => 5.5
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
        $detailItem = Item::has('detail')->first();

        $from = $this->from("/availability/{$detailItem->id}");

        $response = $from->patch("/availability/{$detailItem->id}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/availability');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
