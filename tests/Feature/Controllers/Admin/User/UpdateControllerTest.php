<?php

namespace Tests\Feature\Controllers\Admin\User;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
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
    public function an_user_can_be_updated_with_password_change()
    {
        $user = User::all()->random();

        $newData = [
            'name' => 'Ирина',
            'email' => 'avt.i@priborvbg.ru',
            'password' => '1234567890',
            'password_confirmation' => '1234567890',
            'role_id' => 'Сотрудник КТО'
        ];

        $response = $this->patch("/admin/users/{$user->id}", $newData);
        $user->refresh();

        $userData = $newData;
        unset($userData['password'], $userData['password_confirmation']);
        $userData['role_id'] = Role::getRoleIdByRoleName($newData['role_id']);

        $this->assertDatabaseHas('users', ['id' => $user->id] + $userData);
        $this->assertTrue(Hash::check($newData['password'], $user->password));

        $response->assertRedirect('/admin/users');

        $response = $this->get('/admin/users');
        $response->assertSee(__('messages.successful_update'));
    }

    /** @test */
    public function an_user_can_be_updated_without_password_change()
    {
        $data = [
            'name' => 'Ирина',
            'email' => 'avt.i@priborvbg.ru',
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role_id' => 'Сотрудник КТО'
        ];

        $response = $this->post("/admin/users", $data);

        $user = User::where('email', $data['email'])->first();

        $newData = [
            'name' => 'Наталья',
            'email' => 'avt.n@priborvbg.ru',
            'password' => null,
            'password_confirmation' => null,
            'role_id' => 'Сотрудник экономического отдела'
        ];

        $response = $this->patch("/admin/users/{$user->id}", $newData);
        $user->refresh();

        $newUserData = $newData;
        unset($newUserData['password'], $newUserData['password_confirmation']);
        $newUserData['role_id'] = Role::getRoleIdByRoleName($newData['role_id']);

        $this->assertDatabaseHas('users', ['id' => $user->id] + $newUserData);
        $this->assertTrue(Hash::check($data['password'], $user->password));

        $response->assertRedirect('/admin/users');

        $response = $this->get('/admin/users');
        $response->assertSee(__('messages.successful_update'));
    }
}
