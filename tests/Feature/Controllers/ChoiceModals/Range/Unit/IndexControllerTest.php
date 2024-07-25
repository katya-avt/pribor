<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\Unit;

use App\Models\Range\Unit;
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
    public function response_for_route_unit_choice_is_view_choice_modals_range_unit_index_with_units()
    {
        $units = Unit::all();

        $response = $this->get("/unit-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.unit.index');

        foreach ($units as $unit) {
            $response->assertSee($unit->short_name);
            $response->assertSee($unit->name);
        }
    }
}
