<?php

namespace Tests\Feature\Controllers\Auth;

use App\Models\Users\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class LoginControllerTest extends TestCase
{
    use DatabaseTransactions;

    /** @test */
    public function response_for_route_login_is_view_auth_login_with_login_form()
    {
        $this->withoutExceptionHandling();

        $response = $this->get("/login");
        $response->assertOk();

        $response->assertViewIs('auth.login');
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_email_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'email' => null,
                    'password' => '12345678'
                ]
            ],

            'request_should_fail_when_email_is_not_string' => [
                'passed' => false,
                'data' => [
                    'email' => ['2020-07-15'],
                    'password' => '12345678'
                ]
            ],

            'request_should_fail_when_email_is_not_email' => [
                'passed' => false,
                'data' => [
                    'email' => 'avt.apriborvbgru',
                    'password' => '12345678'
                ]
            ],

            'request_should_fail_when_email_has_more_than_255_characters' => [
                'passed' => false,
                'data' => [
                    'email' => str_repeat('a', 256) . 'avt.a@priborvbg.ru',
                    'password' => '12345678'
                ]
            ],

            'request_should_fail_when_password_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'email' => 'avt.a@priborvbg.ru',
                    'password' => null
                ]
            ],

            'request_should_fail_when_password_is_not_string' => [
                'passed' => false,
                'data' => [
                    'email' => 'avt.a@priborvbg.ru',
                    'password' => ['2020-07-15']
                ]
            ],

            'request_should_fail_when_password_has_less_than_8_characters' => [
                'passed' => false,
                'data' => [
                    'email' => 'avt.a@priborvbg.ru',
                    'password' => '1234567'
                ]
            ],
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
        $response = $this->post('/login', $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect(RouteServiceProvider::HOME);
        } else {
            $response->assertRedirect();
        }
    }

    public function roleProvider()
    {
        return [
            'redirect_to_admin_users_index_after_successful_auth_when_auth_user_is_admin' => [
                'role_name' => 'Админ',
                'redirect_to' => 'admin/users'
            ],

            'redirect_to_items_index_after_successful_auth_when_auth_user_is_engineering_department_officer' => [
                'role_name' => 'Сотрудник КТО',
                'redirect_to' => '/items'
            ],

            'redirect_to_items_index_after_successful_auth_when_auth_user_is_economic_department_officer' => [
                'role_name' => 'Сотрудник экономического отдела',
                'redirect_to' => '/items'
            ],

            'redirect_to_items_index_after_successful_auth_when_auth_user_is_procurement_and_sales_department_officer' => [
                'role_name' => 'Сотрудник отдела снабжения и сбыта',
                'redirect_to' => '/items'
            ]
        ];
    }

    /**
     * @test
     * @dataProvider roleProvider
     * @param string $roleName
     * @param string $redirectTo
     */
    public function redirection_results_as_expected($roleName, $redirectTo)
    {
        $this->withoutExceptionHandling();

        $data = [
            'name' => 'Ирина',
            'email' => 'avt.i@priborvbg.ru',
            'password' => Hash::make('12345678'),
        ];

        $data = $data + ['role_id' => $roleName];

        User::create($data);

        $response = $this->post("/login", [
            'email' => 'avt.i@priborvbg.ru',
            'password' => '12345678'
        ]);

        $response->assertRedirect($redirectTo);
    }

    /** @test */
    public function redirect_to_login_after_successful_logout()
    {
        $this->withoutExceptionHandling();

        $user = User::first();
        $this->actingAs($user);

        $response = $this->post('/logout');

        $response->assertRedirect('/login');
    }
}
