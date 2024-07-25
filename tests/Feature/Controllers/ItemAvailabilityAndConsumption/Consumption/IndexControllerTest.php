<?php

namespace Tests\Feature\Controllers\ItemAvailabilityAndConsumption\Consumption;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
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
    public function response_for_route_consumption_index_is_view_item_availability_and_consumption_consumption_index_with_sorted_in_production_orders()
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

        $inProductionOrders = Order::where('status_id', OrderStatus::IN_PRODUCTION)->get();

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);

        $response = $this->get("/consumption");
        $response->assertOk();

        $response->assertViewIs('item-availability-and-consumption.consumption.index');

        foreach ($inProductionOrders as $inProductionOrder) {
            $response->assertSee($inProductionOrder->code);
            $response->assertSee($inProductionOrder->name);
            $response->assertSee($inProductionOrder->creation_date);
            $response->assertSee($inProductionOrder->customer->name);
        }

        $response->assertSeeInOrder([
            $firstOrder->code,
            $secondOrder->code
        ]);
    }
}
