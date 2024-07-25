<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Route;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CreateControllerTest extends TestCase
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
    public function response_for_route_routes_create_is_view_range_manufacturing_process_routes_create_with_store_form()
    {
        $response = $this->get('/routes/create');
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.routes.create');
    }
}
