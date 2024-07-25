<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_routes_show_is_view_range_manufacturing_process_routes_show_with_single_route()
    {
        $route = Route::has('points')->first();

        $response = $this->get("/routes/{$route->number}");
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.routes.show');

        $response->assertSee($route->number);
        $response->assertSee($route->points->implode(' - '));

        foreach ($route->points as $routePoint) {
            $response->assertSee($routePoint->pivot->point_number);
            $response->assertSee($routePoint->pivot->point_code);
            $response->assertSee($routePoint->pivot->operation_code);
            $response->assertSee($routePoint->pivot->unit_time);
            $response->assertSee($routePoint->pivot->working_time);
            $response->assertSee($routePoint->pivot->lead_time);
            $response->assertSee($routePoint->pivot->rate_code);
        }
    }
}
