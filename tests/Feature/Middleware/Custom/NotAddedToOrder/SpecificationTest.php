<?php

namespace Tests\Feature\Middleware\Custom\NotAddedToOrder;

use App\Models\Range\Specification;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SpecificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function modification_is_available_for_specifications_that_not_added_to_order()
    {
        $specification = Specification::whereNull('added_to_order_at')->first();

        $response = $this->get("/specifications/{$specification->number}/edit");
        $response->assertOk();
    }

    /** @test */
    public function modification_is_not_available_for_specifications_that_added_to_order()
    {
        $specification = Specification::whereNotNull('added_to_order_at')->first();

        $response = $this->get("/specifications/{$specification->number}/edit");
        $response->assertNotFound();
    }
}
