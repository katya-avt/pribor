<?php

namespace Tests\Feature\Middleware\Custom\ItemSpecifications;

use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CoversOnlyForNotCoversTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function covers_list_is_available_for_not_covers()
    {
        $item = Item::whereHas('group', function ($query) {
            $query->where('groups.name', Group::DETAIL);
        })->first();

        $response = $this->get("/items/{$item->id}/covers");
        $response->assertOk();
    }

    /** @test */
    public function covers_list_is_not_available_for_covers()
    {
        $item = Item::whereHas('group', function ($query) {
            $query->where('groups.name', Group::GALVANIC);
        })->first();

        $response = $this->get("/items/{$item->id}/covers");
        $response->assertNotFound();
    }
}
