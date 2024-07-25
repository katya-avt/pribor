<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\ManufacturingProcess\Specification;

use App\Models\Range\Specification;
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
    public function response_for_route_specification_choice_is_view_choice_modals_range_manufacturing_process_specification_index_with_specifications()
    {
        $specifications = Specification::take(10)->get();

        $response = $this->get("/specification-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.manufacturing-process.specification.index');

        foreach ($specifications as $specification) {
            $response->assertSee($specification->number);
        }
    }
}
