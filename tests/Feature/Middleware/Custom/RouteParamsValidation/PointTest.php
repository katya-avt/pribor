<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Range\Operation;
use App\Models\Range\Point;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PointTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function point_base_payment_history_is_available_if_point_has_been_selected_from_list()
    {
        $point = Point::first();

        $response = $this->get("/labor-payment/{$point->code}");
        $response->assertOk();
    }

    /** @test */
    public function point_base_payment_history_is_not_available_if_point_has_not_been_selected_from_list()
    {
        $operation = Operation::first();

        $response = $this->get("/labor-payment/{$operation->code}");
        $response->assertNotFound();
    }
}
