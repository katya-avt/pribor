<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PointNumberTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function route_point_is_available_if_point_number_has_been_selected_from_list()
    {
        $route = Route::whereNull('added_to_order_at')->first();
        $routePoint = $route->points()->first();

        $response = $this->get("/routes/{$route->number}/{$routePoint->pivot->point_number}/edit");
        $response->assertOk();
    }

    /** @test */
    public function route_point_is_not_available_if_point_number_has_not_been_selected_from_list()
    {
        $route = Route::whereNull('added_to_order_at')->first();
        $pointsCnt = $route->points()->count();
        $invalidPointNumber = $pointsCnt + 1;

        $response = $this->get("/routes/{$route->number}/{$invalidPointNumber}/edit");
        $response->assertNotFound();
    }
}
