<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\Operation;

use App\Models\Range\Operation;
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
    public function response_for_route_operation_choice_is_view_choice_modals_range_operation_index_with_operations()
    {
        $operations = Operation::all();

        $response = $this->get("/operation-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.operation.index');

        foreach ($operations as $operation) {
            $response->assertSee($operation->name);
        }
    }
}
