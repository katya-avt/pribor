<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\OrderStatus;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use DatabaseTransactions;

    public function userRoleProvider()
    {
        return [
            'pending_orders_is_available_for_economic_department_officer' => [
                'shouldAuthorize' => true,
                'userRole' => 'Сотрудник экономического отдела',
                'orderStatus' => 'Отложен'
            ],

            'pending_orders_is_not_available_for_admin' => [
                'shouldAuthorize' => false,
                'userRole' => 'Админ',
                'orderStatus' => 'Отложен'
            ],

            'pending_orders_is_not_available_for_engineering_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник КТО',
                'orderStatus' => 'Отложен'
            ],

            'pending_orders_is_not_available_for_procurement_and_sales_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник отдела снабжения и сбыта',
                'orderStatus' => 'Отложен'
            ],

            'in_production_orders_is_available_for_engineering_department_officer' => [
                'shouldAuthorize' => true,
                'userRole' => 'Сотрудник КТО',
                'orderStatus' => 'В производстве'
            ],

            'in_production_orders_is_available_for_economic_department_officer' => [
                'shouldAuthorize' => true,
                'userRole' => 'Сотрудник экономического отдела',
                'orderStatus' => 'В производстве'
            ],

            'in_production_orders_is_available_for_procurement_and_sales_department_officer' => [
                'shouldAuthorize' => true,
                'userRole' => 'Сотрудник отдела снабжения и сбыта',
                'orderStatus' => 'В производстве'
            ],

            'in_production_orders_is_not_available_for_admin' => [
                'shouldAuthorize' => false,
                'userRole' => 'Админ',
                'orderStatus' => 'В производстве'
            ],

            'production_completed_orders_is_available_for_procurement_and_sales_department_officer' => [
                'shouldAuthorize' => true,
                'userRole' => 'Сотрудник отдела снабжения и сбыта',
                'orderStatus' => 'Произведен'
            ],

            'production_completed_orders_is_not_available_for_admin' => [
                'shouldAuthorize' => false,
                'userRole' => 'Админ',
                'orderStatus' => 'Произведен'
            ],

            'production_completed_orders_is_not_available_for_engineering_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник КТО',
                'orderStatus' => 'Произведен'
            ],

            'production_completed_orders_is_not_available_for_economic_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник экономического отдела',
                'orderStatus' => 'Произведен'
            ],

            'on_shipment_orders_is_available_for_procurement_and_sales_department_officer' => [
                'shouldAuthorize' => true,
                'userRole' => 'Сотрудник отдела снабжения и сбыта',
                'orderStatus' => 'На отгрузке'
            ],

            'on_shipment_orders_is_not_available_for_admin' => [
                'shouldAuthorize' => false,
                'userRole' => 'Админ',
                'orderStatus' => 'На отгрузке'
            ],

            'on_shipment_orders_is_not_available_for_engineering_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник КТО',
                'orderStatus' => 'На отгрузке'
            ],

            'on_shipment_orders_is_not_available_for_economic_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник экономического отдела',
                'orderStatus' => 'На отгрузке'
            ],

            'shipped_orders_is_available_for_economic_department_officer' => [
                'shouldAuthorize' => true,
                'userRole' => 'Сотрудник экономического отдела',
                'orderStatus' => 'Отгружен'
            ],

            'shipped_orders_is_not_available_for_admin' => [
                'shouldAuthorize' => false,
                'userRole' => 'Админ',
                'orderStatus' => 'Отгружен'
            ],

            'shipped_orders_is_not_available_for_engineering_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник КТО',
                'orderStatus' => 'Отгружен'
            ],

            'shipped_orders_is_not_available_for_procurement_and_sales_department_officer' => [
                'shouldAuthorize' => false,
                'userRole' => 'Сотрудник отдела снабжения и сбыта',
                'orderStatus' => 'Отгружен'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider userRoleProvider
     * @param bool $shouldAuthorize
     * @param string $userRole
     * @param string $orderStatus
     */
    public function access_results_as_expected($shouldAuthorize, $userRole, $orderStatus)
    {
        $user = User::whereHas('role', function ($query) use ($userRole) {
            $query->where('roles.name', $userRole);
        })->first();

        Sanctum::actingAs($user);

        $orderStatus = OrderStatus::where('name', $orderStatus)->first();

        $response = $this->get("/orders/status/" . $orderStatus->url_param_name);

        if ($shouldAuthorize) {
            $response->assertOk();
        } else {
            $response->assertStatus(403);
        }
    }
}
