<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
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
    public function response_for_route_routes_index_is_view_range_manufacturing_process_routes_index_with_routes()
    {
        $routes = Route::take(10)->get();

        $response = $this->get('/routes');
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.routes.index');

        foreach ($routes as $route) {
            $response->assertSee($route->number);
        }
    }
}
