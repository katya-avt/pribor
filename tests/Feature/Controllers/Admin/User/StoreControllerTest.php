<?php

namespace Tests\Feature\Controllers\Admin\User;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreControllerTest extends TestCase
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
    public function an_user_can_be_stored()
    {
        $data = [
            'name' => 'Ирина',
            'email' => 'avt.i@priborvbg.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role_id' => 'Сотрудник КТО'
        ];

        $response = $this->post("/admin/users", $data);

        $userData = $data;
        unset($userData['password'], $userData['password_confirmation']);
        $userData['role_id'] = Role::getRoleIdByRoleName($data['role_id']);

        $this->assertDatabaseHas('users', $userData);

        $user = User::where('email', $data['email'])->first();
        $this->assertTrue(Hash::check($data['password'], $user->password));

        $response->assertRedirect('/admin/users');

        $response = $this->get('/admin/users');
        $response->assertSee(__('messages.successful_store'));
    }
}
