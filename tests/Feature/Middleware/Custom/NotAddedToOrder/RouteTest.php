<?php

namespace Tests\Feature\Middleware\Custom\NotAddedToOrder;

use App\Models\Range\Route;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RouteTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function modification_is_available_for_routes_that_not_added_to_order()
    {
        $route = Route::whereNull('added_to_order_at')->first();

        $response = $this->get("/routes/{$route->number}/edit");
        $response->assertOk();
    }

    /** @test */
    public function modification_is_not_available_for_routes_that_added_to_order()
    {
        $route = Route::whereNotNull('added_to_order_at')->first();

        $response = $this->get("/routes/{$route->number}/edit");
        $response->assertNotFound();
    }
}
