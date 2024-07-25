<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Route;

use App\Models\Range\Cover;
use App\Models\Range\Route;
use App\Models\Range\Specification;
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

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
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
        $route = Route::whereNull('added_to_order_at')->first();

        $from = $this->from("/routes/{$route->number}/edit");

        $response = $from->patch("/routes/{$route->number}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/routes');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_route_number_is_not_unique_in_specifications()
    {
        $specification = Specification::first();

        $route = Route::whereNull('added_to_order_at')->first();

        $data = [
            'number' => $specification->number
        ];

        $from = $this->from("/routes/{$route->number}/edit");

        $response = $from->patch("/routes/{$route->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_route_number_is_not_unique_in_routes()
    {
        $firstRoute = Route::whereNull('added_to_order_at')->first();

        $secondRoute = Route::whereNull('added_to_order_at')
            ->where('number', '<>', $firstRoute->number)
            ->first();

        $secondRouteData = [
            'number' => $secondRoute->number
        ];


        $from = $this->from("/routes/{$firstRoute->number}/edit");

        $response = $from->patch("/routes/{$firstRoute->number}", $secondRouteData);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_route_number_is_not_updated()
    {
        $route = Route::whereNull('added_to_order_at')->first();

        $data = [
            'number' => $route->number
        ];

        $from = $this->from("/routes/{$route->number}/edit");

        $response = $from->patch("/routes/{$route->number}", $data);
        $response->assertRedirect('/routes');
    }

    /** @test */
    public function request_should_fail_when_route_number_is_not_unique_in_covers()
    {
        $cover = Cover::first();

        $route = Route::whereNull('added_to_order_at')->first();

        $data = [
            'number' => $cover->number
        ];

        $from = $this->from("/routes/{$route->number}/edit");

        $response = $from->patch("/routes/{$route->number}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
