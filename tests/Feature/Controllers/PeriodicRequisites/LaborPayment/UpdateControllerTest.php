<?php

namespace Tests\Feature\Controllers\PeriodicRequisites\LaborPayment;

use App\Models\Range\Point;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateControllerTest extends TestCase
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
    public function a_point_base_payment_can_be_updated()
    {
        $point = Point::first();

        $newData = [
            'base_payment' => 6.5
        ];

        $response = $this->patch("/labor-payment/{$point->code}", $newData);
        $point->refresh();

        $this->assertDatabaseHas('points', ['code' => $point->code] + $newData);

        $response->assertRedirect('/labor-payment');

        $response = $this->get('/labor-payment');
        $response->assertSee(__('messages.successful_update'));
    }
}
