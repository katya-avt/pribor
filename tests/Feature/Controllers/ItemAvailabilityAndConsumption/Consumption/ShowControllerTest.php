<?php

namespace Tests\Feature\Controllers\ItemAvailabilityAndConsumption\Consumption;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Database\Seeders\SeedersForTests\AssemblyItemSeeder;
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
    }

    /** @test */
    public function response_for_route_consumption_show_is_view_item_availability_and_consumption_consumption_show_with_in_production_orders_components()
    {
        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);

        Order::query()->update([
            'status_id' => OrderStatus::PRODUCTION_COMPLETED
        ]);

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

        $response = $this->get("/consumption/{$secondOrder->id}");
        $response->assertOk();

        $response->assertViewIs('item-availability-and-consumption.consumption.show');

        $response->assertSee($secondOrder->code);
        $response->assertSee($secondOrder->name);
        $response->assertSee($secondOrder->creation_date);
        $response->assertSee($secondOrder->closing_date);
        $response->assertSee($secondOrder->launch_date);
        $response->assertSee($secondOrder->customer->name);

        $orderItem = $secondOrder->items()->first();

        $response->assertSee($orderItem->drawing);
        $response->assertSee($orderItem->name);
        $response->assertSee($data['cnt']);
        $response->assertSee($orderItem->cnt);
        $response->assertSee(abs($orderItem->cnt - $data['cnt'] * 2));

        $metalItem = Item::getItemByDrawing('Металл');

        $metalItemCntInDetail = 0.28462;

        $response->assertSee($metalItem->drawing);
        $response->assertSee($metalItem->name);
        $response->assertSee(round($data['cnt'] * $metalItemCntInDetail, 2));
        $response->assertSee($metalItem->cnt);
        $response->assertSee(abs($metalItem->cnt - round($data['cnt'] * $metalItemCntInDetail, 2) * 2));

        $galvanicItem = Item::getItemByDrawing('Гальваническое покрытие');

        $galvanicItemCntInDetail = 165.37 * 0.00008;

        $response->assertSee($galvanicItem->drawing);
        $response->assertSee($galvanicItem->name);
        $response->assertSee(round($data['cnt'] * $galvanicItemCntInDetail, 2));
        $response->assertSee($galvanicItem->cnt);
        $response->assertSee(abs($galvanicItem->cnt - round($data['cnt'] * $galvanicItemCntInDetail, 2) * 2));
    }
}
