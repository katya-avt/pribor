<?php

namespace Tests\Feature\Middleware\Custom;

use App\Models\Range\Group;
use App\Models\Range\Specification;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DetailSpecificationTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function addition_a_new_item_to_detail_specification_is_available_if_it_is_empty()
    {
        $specification = Specification::has('items')->whereHas('relatedItems', function ($query) {
            $query->whereNull('items.added_to_order_at')->whereHas('group', function ($query) {
                $query->where('groups.name', Group::DETAIL);
            });
        })->first();

        $specification->items()->detach();

        $response = $this->get("/specifications/{$specification->number}/create");
        $response->assertOk();
    }

    /** @test */
    public function addition_a_new_item_to_detail_specification_is_not_available_if_it_already_has_one_item()
    {
        $specification = Specification::has('items')->whereHas('relatedItems', function ($query) {
            $query->whereNull('items.added_to_order_at')->whereHas('group', function ($query) {
                $query->where('groups.name', Group::DETAIL);
            });
        })->first();

        $response = $this->get("/specifications/{$specification->number}/create");
        $response->assertNotFound();
    }
}
