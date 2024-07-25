<?php

namespace Tests\Feature\Requests\Admin\User;

use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreRequestTest extends TestCase
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
        return [
            'request_should_fail_when_name_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'name' => null,
                    'email' => 't.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_name_is_not_a_string' => [
                'passed' => false,
                'data' => [
                    'name' => ['Не строка'],
                    'email' => 't.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_name_has_greater_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'name' => str_repeat('а', 256),
                    'email' => 't.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => null,
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_is_not_a_string' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => ['Не строка'],
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_is_not_an_email' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 'tspriborvbgru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_have_not_characters_before_first_dot' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => '.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_have_not_characters_after_first_dot' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 't.@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_have_not_first_dot' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 'ts@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_have_not_@priborvbg.ru' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 't.s',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_email_has_greater_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => str_repeat('t', 256) . 't.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_password_is_not_a_string' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 't.s@priborvbg.ru',
                    'password' => ['Не строка'],
                    'password_confirmation' => ['Не строка'],
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_password_has_less_than_8_characters' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 't.s@priborvbg.ru',
                    'password' => '1234567',
                    'password_confirmation' => '1234567',
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_password_has_not_confirmed' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 't.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => null,
                    'role_id' => 'Сотрудник КТО',
                ]
            ],

            'request_should_fail_when_role_id_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 't.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => null,
                ]
            ],

            'request_should_fail_when_role_id_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'name' => 'Имя',
                    'email' => 't.s@priborvbg.ru',
                    'password' => '12345678',
                    'password_confirmation' => '12345678',
                    'role_id' => 'Значение не из списка.',
                ]
            ]
        ];
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $from = $this->from('/admin/users/create');

        $response = $from->post("/admin/users", $mockedRequestData);

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
        $user = User::first();

        $data = [
            'name' => 'Имя',
            'email' => $user->email,
            'password' => '12345678',
            'password_confirmation' => '12345678',
            'role_id' => 'Сотрудник КТО'
        ];

        $from = $this->from('/admin/users/create');

        $response = $from->post("/admin/users", $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_password_is_not_provided()
    {
        $data = [
            'name' => 'Имя',
            'email' => 't.s@priborvbg.ru',
            'password' => null,
            'password_confirmation' => null,
            'role_id' => 'Сотрудник КТО'
        ];

        $from = $this->from('/admin/users/create');

        $response = $from->post("/admin/users", $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
