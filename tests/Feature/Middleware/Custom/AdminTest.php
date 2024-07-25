<?php

namespace Tests\Feature\Middleware\Custom;

use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AdminTest extends TestCase
{
    use DatabaseTransactions;

    public function userRoleProvider()
    {
        return [
            'admin_route_is_available_for_admin' => [
                'shouldAuthorize' => true,
                'userRole' => 'Админ'
            ],

            'admin_route_is_not_available_for_engineering_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник КТО'
            ],

            'admin_route_is_not_available_for_economic_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник экономического отдела'
            ],

            'admin_route_is_not_available_for_procurement_and_sales_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник отдела снабжения и сбыта'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider userRoleProvider
     * @param bool $shouldAuthorize
     * @param string $userRole
     */
    public function access_results_as_expected($shouldAuthorize, $userRole)
    {
        $user = User::whereHas('role', function ($query) use ($userRole) {
            $query->where('roles.name', $userRole);
        })->first();

        Sanctum::actingAs($user);

        $response = $this->get('/admin/users');

        if ($shouldAuthorize) {
            $response->assertOk();
        } else {
            $response->assertStatus(403);
        }
    }
}
