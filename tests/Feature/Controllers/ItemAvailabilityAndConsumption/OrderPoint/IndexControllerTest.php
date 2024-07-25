<?php

namespace Tests\Feature\Controllers\ItemAvailabilityAndConsumption\OrderPoint;

use App\Models\Orders\Order;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Database\Seeders\SeedersForTests\AssemblyItemSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();
    }

    /** @test */
    public function response_for_route_order_point_index_is_view_item_availability_and_consumption_order_point_index_with_purchased_items_order_point()
    {
        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);

        $this->seed(AssemblyItemSeeder::class);

        $firstOrder = Order::create([
            'code' => 'Заказ с меньшим запасом времени',
            'name' => 'Заказ с меньшим запасом времени',
            'launch_date' => null,
            'closing_date' => Carbon::now()->addYears(1)->toDateString(),
            'completion_date' => null,
            'note' => null,
            'customer_inn' => 'АВТ_П3_17',
        ]);

        $data = [
            'item_id' => 'Металлическая деталь',
            'per_unit_price' => 5085.43,
            'cnt' => 85,
        ];

        $response = $this->post("/orders/{$firstOrder->id}", $data);
        $firstOrder->refresh();

        $firstOrder->putIntoProduction();
        $firstOrder->refresh();

        $secondOrder = Order::create([
            'code' => 'Заказ с большим запасом времени',
            'name' => 'Заказ с большим запасом времени',
            'launch_date' => null,
            'closing_date' => Carbon::now()->addYears(2)->toDateString(),
            'completion_date' => null,
            'note' => null,
            'customer_inn' => 'АВТ_П3_17',
        ]);

        $response = $this->post("/orders/{$secondOrder->id}", $data);
        $secondOrder->refresh();

        $secondOrder->putIntoProduction();
        $secondOrder->refresh();

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);

        $response = $this->get("/order-point");
        $response->assertOk();

        $response->assertViewIs('item-availability-and-consumption.order-point.index');

        $metalItem = Item::getItemByDrawing('Металл');

        $metalItemCntInDetail = 0.28462;
        $requiredCnt = round($data['cnt'] * $metalItemCntInDetail, 2) * 2;

        $response->assertSee($metalItem->drawing);
        $response->assertSee($metalItem->name);
        $response->assertSee($metalItem->cnt);
        $response->assertSee($requiredCnt);
        $response->assertSee($metalItem->purchasedItem->order_point);
        $response->assertSee(abs($requiredCnt + $metalItem->purchasedItem->order_point - $metalItem->cnt));

        $chemicalItem = Item::getItemByDrawing('Химикат');

        $galvanicItemCntInDetail = 165.37 * 0.00008;
        $chemicalItemCntInGalvanicItem = 0.20277;
        $requiredCnt = round($data['cnt'] * $galvanicItemCntInDetail * $chemicalItemCntInGalvanicItem, 2) * 2;

        $response->assertSee($chemicalItem->drawing);
        $response->assertSee($chemicalItem->name);
        $response->assertSee($chemicalItem->cnt);
        $response->assertSee($requiredCnt);
        $response->assertSee($chemicalItem->purchasedItem->order_point);
        $response->assertSee(abs($requiredCnt + $chemicalItem->purchasedItem->order_point - $chemicalItem->cnt));
    }
}
