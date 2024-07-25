<?php

namespace Tests\Feature\Controllers\PeriodicRequisites\LaborPayment;

use App\Models\Range\Point;
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
    public function response_for_route_periodic_requisites_labor_payment_index_is_view_periodic_requisites_labor_payment_index_with_points()
    {
        $points = Point::take(10)->get();

        $response = $this->get('/labor-payment');
        $response->assertOk();

        $response->assertViewIs('periodic-requisites.labor-payment.index');

        foreach ($points as $point) {
            $response->assertSee($point->code);
            $response->assertSee($point->name);
            $response->assertSee($point->base_payment);
        }
    }
}
