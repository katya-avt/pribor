<?php

namespace Tests\Feature\Controllers\PeriodicRequisites\LaborPayment;

use App\Models\Range\Point;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditControllerTest extends TestCase
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
    public function response_for_route_periodic_requisites_labor_payment_edit_is_view_periodic_requisites_labor_payment_edit_with_update_form()
    {
        $point = Point::all()->random();

        $response = $this->get("/labor-payment/{$point->code}/edit");
        $response->assertOk();

        $response->assertViewIs('periodic-requisites.labor-payment.edit');

        $response->assertSee($point->code);
        $response->assertSee($point->name);
        $response->assertSee($point->base_payment);
    }
}
