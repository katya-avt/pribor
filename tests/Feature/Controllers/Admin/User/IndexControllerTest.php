<?php

namespace Tests\Feature\Controllers\Admin\User;

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

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_admin_users_index_is_view_admin_users_index_with_users()
    {
        $users = User::all();

        $response = $this->get("/admin/users");
        $response->assertOk();

        $response->assertViewIs('admin.users.index');

        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
            $response->assertSee($user->role->name);
        }
    }
}
