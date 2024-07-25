<?php

namespace Tests\Feature\Controllers\PeriodicRequisites\LaborPayment;

use App\Models\Range\Point;
use App\Models\Range\PointBasePaymentHistory;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowControllerTest extends TestCase
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
    public function response_for_route_periodic_requisites_labor_payment_show_is_view_periodic_requisites_labor_payment_show_with_change_history_chart()
    {
        $point = Point::first();

        $point->basePaymentChanges()->delete();

        $now = Carbon::now();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $now->toDateTimeString(),
            'new_value' => 5,
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $now->copy()->addYear()->toDateTimeString(),
            'new_value' => 10,
        ]);

        $point->refresh();

        $response = $this->get("/labor-payment/{$point->code}");
        $response->assertOk();

        $response->assertViewIs('periodic-requisites.labor-payment.show');

        $response->assertSee($point->code);
        $response->assertSee($point->name);
        $response->assertSee($point->base_payment);

        $changesTime = json_encode([
            $now->format('d.m.Y H:i:s'),
            $now->copy()->addYear()->format('d.m.Y H:i:s')
        ]);
        $response->assertSee($changesTime);

        $newValues = json_encode([
            "5.00",
            "10.00"
        ]);
        $response->assertSee($newValues);
    }
}
