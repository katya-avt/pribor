<?php

namespace Tests\Feature\Controllers\PeriodicRequisites\PurchasePrice;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class EditControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_periodic_requisites_purchase_price_edit_is_view_periodic_requisites_purchase_price_edit_with_update_form()
    {
        $item = Item::has('purchasedItem')->with('purchasedItem')->first();

        $response = $this->get("/purchase-price/{$item->id}/edit");
        $response->assertOk();

        $response->assertViewIs('periodic-requisites.purchase-price.edit');

        $response->assertSee($item->drawing);
        $response->assertSee($item->name);
        $response->assertSee($item->purchasedItem->purchase_price);
    }
}
