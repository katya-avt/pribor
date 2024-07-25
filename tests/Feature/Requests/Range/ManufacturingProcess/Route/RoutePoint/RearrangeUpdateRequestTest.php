<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RearrangeUpdateRequestTest extends TestCase
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
            'request_should_fail_when_route_point_number_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'order' => [
                        1 => null,
                        2 => 1,
                        3 => 3,
                        4 => 4
                    ]
                ]
            ],

            'request_should_fail_when_route_point_number_is_not_integer' => [
                'passed' => false,
                'data' => [
                    'order' => [
                        1 => 2.5,
                        2 => 1,
                        3 => 3,
                        4 => 4
                    ]
                ]
            ],

            'request_should_fail_when_route_point_number_less_than_1' => [
                'passed' => false,
                'data' => [
                    'order' => [
                        1 => 0,
                        2 => 1,
                        3 => 3,
                        4 => 4
                    ]
                ]
            ],

            'request_should_fail_when_route_point_number_exceed_current_quantity' => [
                'passed' => false,
                'data' => [
                    'order' => [
                        1 => 5,
                        2 => 1,
                        3 => 3,
                        4 => 4
                    ]
                ]
            ],

            'request_should_fail_when_route_point_number_repeat' => [
                'passed' => false,
                'data' => [
                    'order' => [
                        1 => 1,
                        2 => 1,
                        3 => 3,
                        4 => 4
                    ]
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
        $route = Route::whereNull('added_to_order_at')->first();

        $from = $this->from("/routes/{$route->number}/rearrange");

        $response = $from->patch("/routes/{$route->number}/rearrange", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect("/routes/{$route->number}");
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
