<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Range\Cover;
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
    public function specification_is_available_if_it_has_been_selected_from_list()
    {
        $specification = Specification::first();

        $response = $this->get("/specifications/{$specification->number}");
        $response->assertOk();
    }

    /** @test */
    public function specification_is_not_available_if_it_has_not_been_selected_from_list()
    {
        $cover = Cover::first();

        $response = $this->get("/specifications/{$cover->number}");
        $response->assertNotFound();
    }
}
