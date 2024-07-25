<?php

namespace Tests\Feature\Requests\Admin\User;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ADMIN)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return (new StoreRequestTest())->validationProvider();
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $user = User::first();

        $from = $this->from("/admin/users/{$user->id}/edit");

        $response = $from->patch("/admin/users/{$user->id}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/admin/users');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_email_is_not_unique()
    {
        $firstUser = User::first();
        $secondUser = User::where('id', '<>', $firstUser->id)->first();

        $data = [
            'name' => 'Имя',
            'email' => $secondUser->email,
            'role_id' => 'Сотрудник КТО'
        ];

        $from = $this->from("/admin/users/{$firstUser->id}/edit");

        $response = $from->patch("/admin/users/{$firstUser->id}", $data);
        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_not_fail_when_unique_email_is_not_updated()
    {
        $user = User::first();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => 'Сотрудник КТО'
        ];

        $from = $this->from("/admin/users/{$user->id}/edit");

        $response = $from->patch("/admin/users/{$user->id}", $data);
        $response->assertRedirect("/admin/users");
        $response->assertSessionDoesntHaveErrors();
    }

    /** @test */
    public function request_should_not_fail_when_password_is_not_updated()
    {
        $user = User::first();

        $data = [
            'name' => $user->name,
            'email' => $user->email,
            'role_id' => 'Сотрудник КТО'
        ];

        $from = $this->from("/admin/users/{$user->id}/edit");

        $response = $from->patch("/admin/users/{$user->id}", $data);
        $response->assertRedirect("/admin/users");
        $response->assertSessionDoesntHaveErrors();
    }
}
