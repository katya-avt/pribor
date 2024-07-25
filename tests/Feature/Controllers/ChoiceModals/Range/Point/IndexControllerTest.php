<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\Point;

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

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_point_choice_is_view_choice_modals_range_point_index_with_points()
    {
        $points = Point::all();

        $response = $this->get("/point-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.point.index');

        foreach ($points as $point) {
            $response->assertSee($point->name);
        }
    }
}
