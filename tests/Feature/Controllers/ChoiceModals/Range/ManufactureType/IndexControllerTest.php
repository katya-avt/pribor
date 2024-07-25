<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\ManufactureType;

use App\Models\Range\ManufactureType;
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
    public function response_for_route_manufacture_type_choice_is_view_choice_modals_range_manufacture_type_index_with_manufacture_types()
    {
        $manufactureTypes = ManufactureType::all();

        $response = $this->get("/manufacture-type-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.manufacture-type.index');

        foreach ($manufactureTypes as $manufactureType) {
            $response->assertSee($manufactureType->name);
        }
    }
}
