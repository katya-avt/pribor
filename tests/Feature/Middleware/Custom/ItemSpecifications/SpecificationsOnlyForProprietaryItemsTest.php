<?php

namespace Tests\Feature\Middleware\Custom\ItemSpecifications;

use App\Models\Range\Item;
use App\Models\Range\ItemType;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SpecificationsOnlyForProprietaryItemsTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function specifications_list_is_available_for_proprietary_items()
    {
        $item = Item::whereHas('itemType', function ($query) {
            $query->where('item_types.name', ItemType::PROPRIETARY);
        })->first();

        $response = $this->get("/items/{$item->id}/specifications");
        $response->assertOk();
    }

    /** @test */
    public function specifications_list_is_not_available_for_not_proprietary_items()
    {
        $item = Item::whereHas('itemType', function ($query) {
            $query->where('item_types.name', ItemType::PURCHASED);
        })->first();

        $response = $this->get("/items/{$item->id}/specifications");
        $response->assertNotFound();
    }
}
