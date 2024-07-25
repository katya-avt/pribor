<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route\RoutePoint;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditControllerTest extends TestCase
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
    public function response_for_routes_route_points_edit_is_view_range_manufacturing_process_routes_route_points_edit_with_update_form()
    {
        $route = Route::has('points')->whereNull('added_to_order_at')->first();
        $routePoint = $route->points()->first();

        $response = $this->get("/routes/{$route->number}/{$routePoint->pivot->point_number}/edit");
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.routes.route-points.edit');

        $response->assertSee($routePoint->pivot->point_code);
        $response->assertSee($routePoint->pivot->operation_code);
        $response->assertSee($routePoint->pivot->unit_time);
        $response->assertSee($routePoint->pivot->working_time);
        $response->assertSee($routePoint->pivot->lead_time);
        $response->assertSee($routePoint->pivot->rate_code);
    }
}
