<?php

namespace Tests\Feature\Controllers\ChoiceModals\Range\MainWarehouse;

use App\Models\Range\MainWarehouse;
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
    public function response_for_route_main_warehouse_choice_is_view_choice_modals_range_main_warehouse_index_with_main_warehouses()
    {
        $mainWarehouses = MainWarehouse::all();

        $response = $this->get("/main-warehouse-choice");
        $response->assertOk();

        $response->assertViewIs('choice-modals.range.main-warehouse.index');

        foreach ($mainWarehouses as $mainWarehouse) {
            $response->assertSee($mainWarehouse->name);
        }
    }
}
