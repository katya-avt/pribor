<?php

namespace Tests\Feature\Controllers\Admin\MarkedForDeletion\User;

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
    public function response_for_route_admin_marked_for_deletion_users_index_is_view_admin_marked_for_deletion_users_index_with_marked_for_deletion_users()
    {
        $users = User::onlyTrashed()->get();

        $response = $this->get("/admin/marked-for-deletion/users");
        $response->assertOk();

        $response->assertViewIs('admin.marked-for-deletion.users.index');

        foreach ($users as $user) {
            $response->assertSee($user->name);
            $response->assertSee($user->email);
            $response->assertSee($user->role->name);
        }
    }
}
