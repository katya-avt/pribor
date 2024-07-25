<?php

namespace Tests\Feature\Controllers\Admin\User;

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

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_admin_users_edit_is_view_admin_users_edit_with_update_form()
    {
        $user = User::all()->random();

        $response = $this->get("/admin/users/{$user->id}/edit");
        $response->assertOk();

        $response->assertViewIs('admin.users.edit');

        $response->assertSee($user->name);
        $response->assertSee($user->email);
        $response->assertSee($user->role->name);
    }
}
