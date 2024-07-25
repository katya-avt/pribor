<?php

namespace Tests\Feature\Requests\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
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
        $routePoint = $route->points->first();

        $from = $this->from("/routes/{$route->number}/{$routePoint->pivot->point_number}/edit");

        $response = $from->patch("/routes/{$route->number}/{$routePoint->pivot->point_number}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect("/routes/{$route->number}");
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
