<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Specification;

use App\Models\Range\Cover;
use App\Models\Range\Route;
use App\Models\Range\Specification;
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
            'request_should_fail_when_number_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'number' => null
                ]
            ],

            'request_should_fail_when_number_has_not_only_digits' => [
                'passed' => false,
                'data' => [
                    'number' => '1111111a'
                ]
            ],

            'request_should_fail_when_number_has_not_only_digits_after_symbol_-' => [
                'passed' => false,
                'data' => [
                    'number' => '1111111-1a'
                ]
            ],

            'request_should_fail_when_number_has_less_than_6_characters' => [
                'passed' => false,
                'data' => [
                    'number' => '11111'
                ]
            ],

            'request_should_fail_when_number_has_greater_than_64_characters' => [
                'passed' => false,
                'data' => [
                    'number' => str_repeat('1', 65)
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     */
    public function validation_results_as_expected($shouldPass)
    {
        $from = $this->from('/specifications/create');

        $response = $from->post('/specifications');

        if ($shouldPass) {
            $response->assertRedirect('/specifications');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_unique_in_specifications()
    {
        $specification = Specification::first();

        $data = [
            'number' => $specification->number
        ];

        $from = $this->from('/specifications/create');

        $response = $from->post('/specifications', $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_unique_in_covers()
    {
        $cover = Cover::first();

        $data = [
            'number' => $cover->number
        ];

        $from = $this->from('/specifications/create');

        $response = $from->post('/specifications', $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_cover_number_is_not_unique_in_routes()
    {
        $route = Route::first();

        $data = [
            'number' => $route->number
        ];

        $from = $this->from('/specifications/create');

        $response = $from->post('/specifications', $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
