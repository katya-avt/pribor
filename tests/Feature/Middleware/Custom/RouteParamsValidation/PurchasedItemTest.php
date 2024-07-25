<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PurchasedItemTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function purchased_item_is_available_if_it_has_been_selected_from_list()
    {
        $purchasedItem = Item::has('purchasedItem')->first();

        $response = $this->get("/purchased-items/{$purchasedItem->id}/edit");
        $response->assertOk();
    }

    /** @test */
    public function purchased_item_is_not_available_if_it_has_not_been_selected_from_list()
    {
        $item = Item::doesntHave('purchasedItem')->first();

        $response = $this->get("/purchased-items/{$item->id}/edit");
        $response->assertNotFound();
    }
}
