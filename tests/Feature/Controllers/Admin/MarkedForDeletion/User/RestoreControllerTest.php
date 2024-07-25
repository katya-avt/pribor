<?php

namespace Tests\Feature\Controllers\Admin\MarkedForDeletion\User;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class RestoreControllerTest extends TestCase
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
    public function a_marked_for_deletion_user_can_be_restored()
    {
        $deletedUser = User::onlyTrashed()->get()->random();

        $response = $this->patch("/admin/marked-for-deletion/users/{$deletedUser->id}");
        $deletedUser->refresh();

        $restoredUser = User::find($deletedUser->id);
        $this->assertNotNull($restoredUser);

        $response->assertRedirect('/admin/marked-for-deletion/users');

        $response = $this->get('/admin/marked-for-deletion/users');
        $response->assertSee(__('messages.successful_restore'));
    }
}
