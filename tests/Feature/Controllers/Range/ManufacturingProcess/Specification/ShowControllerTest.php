<?php

namespace Tests\Feature\Controllers\Range\ManufacturingProcess\Specification;

use App\Models\Range\Specification;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowControllerTest extends TestCase
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
    public function response_for_route_specifications_show_is_view_range_manufacturing_process_specifications_show_with_single_specification()
    {
        $specification = Specification::has('items')->first();

        $response = $this->get("/specifications/{$specification->number}");
        $response->assertOk();

        $response->assertViewIs('range.manufacturing-process.specifications.show');

        $response->assertSee($specification->number);

        foreach ($specification->items as $specificationItem) {
            $response->assertSee($specificationItem->group->name);
            $response->assertSee($specificationItem->drawing);
            $response->assertSee($specificationItem->name);
            $response->assertSee($specificationItem->pivot->cnt);
            $response->assertSee($specificationItem->unit->short_name);
        }
    }
}
