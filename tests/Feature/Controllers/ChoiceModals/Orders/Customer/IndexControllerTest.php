<?php

namespace Tests\Feature\Controllers\ChoiceModals\Orders\Customer;

use App\Models\Orders\Customer;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_customer_choice_is_view_choice_modals_orders_customer_index_with_customers()
    {
        $customers = Customer::all();

        $response = $this->get("/customer-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.orders.customer.index');

        foreach ($customers as $customer) {
            $response->assertSee($customer->name);
        }
    }
}
