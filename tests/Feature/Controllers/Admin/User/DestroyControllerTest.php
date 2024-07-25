<?php

namespace Tests\Feature\Controllers\Admin\User;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyControllerTest extends TestCase
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
    public function an_user_can_be_deleted()
    {
        $user = User::all()->random();

        $response = $this->delete("/admin/users/{$user->id}");
        $user->refresh();

        $deletedUser = User::find($user->id);
        $this->assertNull($deletedUser);

        $response->assertRedirect('/admin/users');

        $response = $this->get('/admin/users');
        $response->assertSee(__('messages.successful_delete'));
    }
}
