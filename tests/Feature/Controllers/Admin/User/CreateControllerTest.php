<?php

namespace Tests\Feature\Controllers\Admin\User;

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

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_admin_users_create_is_view_admin_users_create_with_store_form()
    {
        $response = $this->get('/admin/users/create');
        $response->assertOk();

        $response->assertViewIs('admin.users.create');
    }
}
