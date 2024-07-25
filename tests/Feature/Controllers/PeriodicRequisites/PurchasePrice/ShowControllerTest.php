<?php

namespace Tests\Feature\Controllers\PeriodicRequisites\PurchasePrice;

use App\Models\Range\Item;
use App\Models\Range\PurchasedItem;
use App\Models\Range\PurchasedItemPurchasePriceHistory;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowControllerTest extends TestCase
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
    public function response_for_route_periodic_requisites_purchase_price_show_is_view_periodic_requisites_purchase_price_show_with_change_history_chart()
    {
        $item = Item::has('purchasedItem')->with('purchasedItem')->first();
        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItem->purchasePriceChanges()->delete();

        $now = Carbon::now();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $now->toDateTimeString(),
            'new_value' => 5,
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $now->copy()->addYear()->toDateTimeString(),
            'new_value' => 10,
        ]);

        $item->refresh();

        $response = $this->get("/purchase-price/{$item->id}");
        $response->assertOk();

        $response->assertViewIs('periodic-requisites.purchase-price.show');

        $response->assertSee($item->drawing);
        $response->assertSee($item->name);
        $response->assertSee($item->purchasedItem->purchase_price);

        $changesTime = json_encode([
            $now->format('d.m.Y H:i:s'),
            $now->copy()->addYear()->format('d.m.Y H:i:s')
        ]);
        $response->assertSee($changesTime);

        $newValues = json_encode([
            "5.00",
            "10.00"
        ]);
        $response->assertSee($newValues);
    }
}
