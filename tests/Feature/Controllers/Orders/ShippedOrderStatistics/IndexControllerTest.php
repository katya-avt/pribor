<?php

namespace Tests\Feature\Controllers\Orders\ShippedOrderStatistics;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class IndexControllerTest extends TestCase
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
    public function response_for_route_shipped_order_statistics_is_view_orders_shipped_order_statistics_index_with_statistics()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $firstShippedOrder = Order::create([
            'code' => 'Заказ, отгруженный с нарушением срока',
            'name' => 'Заказ, отгруженный с нарушением срока',
            'launch_date' => '2024-01-01',
            'closing_date' => '2024-12-12',
            'completion_date' => '2025-12-12',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $firstShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $firstShippedOrder->refresh();

        $secondShippedOrder = Order::create([
            'code' => 'Заказ, отгруженный в срок',
            'name' => 'Заказ, отгруженный в срок',
            'launch_date' => '2024-01-01',
            'closing_date' => '2024-12-12',
            'completion_date' => '2024-10-12',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $secondShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $secondShippedOrder->refresh();

        $thirdShippedOrder = Order::create([
            'code' => 'Еще один заказ, отгруженный в срок',
            'name' => 'Еще один заказ, отгруженный в срок',
            'launch_date' => '2024-01-01',
            'closing_date' => '2024-12-12',
            'completion_date' => '2024-10-12',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $thirdShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $thirdShippedOrder->refresh();

        $response = $this->get('/shipped-order-statistics');
        $response->assertOk();

        $response->assertViewIs('orders.shipped-order-statistics.index');

        //amount-cost
        $totalProfit = (50000.50 - 25000.25) * 3;
        //AVG(((amount-cost)/amount) * 100)
        $averageProfitability = round(((50000.50 - 25000.25) / 50000.50) * 100);
        $ordersShippedOutOfTimeCount = 1;

        $response->assertSee($totalProfit);
        $response->assertSee($averageProfitability);
        $response->assertSee($ordersShippedOutOfTimeCount);

        $chartLabels = json_encode(["АВТ_П3_17", "АВТ_П3_18"]);
        $response->assertSee($chartLabels);

        $chartValues = json_encode(["33.3333", "66.6667"]);
        $response->assertSee($chartValues);
    }
}
