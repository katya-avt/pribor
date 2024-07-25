<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Range\Cover;
use App\Models\Range\Specification;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CoverItemTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function cover_item_is_available_if_it_has_been_selected_from_list()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();
        $coverItem = $cover->items()->first();

        $response = $this->get("/covers/{$cover->number}/{$coverItem->id}/edit");
        $response->assertOk();
    }

    /** @test */
    public function cover_item_is_not_available_if_it_has_not_been_selected_from_list()
    {
        $cover = Cover::whereNull('added_to_order_at')->first();

        $specification = Specification::whereNull('added_to_order_at')->first();
        $specificationItem = $specification->items()->first();

        $response = $this->get("/covers/{$cover->number}/{$specificationItem->id}/edit");
        $response->assertNotFound();
    }
}
