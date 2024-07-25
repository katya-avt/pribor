<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class MarkedForDeletionUserTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function marked_for_deletion_user_is_available_if_it_has_been_selected_from_list()
    {
        $user = User::onlyTrashed()->first();

        $response = $this->patch("/admin/marked-for-deletion/users/{$user->id}");
        $response->assertRedirect();
    }

    /** @test */
    public function marked_for_deletion_user_is_not_available_if_it_has_not_been_selected_from_list()
    {
        $user = User::first();

        $response = $this->patch("/admin/marked-for-deletion/users/{$user->id}");
        $response->assertNotFound();
    }
}
