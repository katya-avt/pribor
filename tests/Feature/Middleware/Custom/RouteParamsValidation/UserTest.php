<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function user_is_available_if_it_has_been_selected_from_list()
    {
        $user = User::first();

        $response = $this->get("/admin/users/{$user->id}/edit");
        $response->assertOk();
    }

    /** @test */
    public function user_is_not_available_if_it_has_not_been_selected_from_list()
    {
        $user = User::onlyTrashed()->first();

        $response = $this->get("/admin/users/{$user->id}/edit");
        $response->assertNotFound();
    }
}
