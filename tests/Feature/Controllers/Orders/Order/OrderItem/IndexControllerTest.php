<?php

namespace Tests\Feature\Controllers\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Services\Orders\Order\OrderItem\ModifyService;
use Database\Seeders\SeedersForTests\AssemblyItemSeeder;
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

    public function orderStatusProvider()
    {
        return [
            'response_for_route_orders_order_items_index_is_view_orders_orders_order_items_index_with_pending_order_items' => [
                'role_name' => 'Сотрудник экономического отдела',
                'order_status_name' => 'Отложен',
                'action' => 'В производство',
            ],

            'response_for_route_orders_order_items_index_is_view_orders_orders_order_items_index_with_in_production_orders' => [
                'role_name' => 'Сотрудник отдела снабжения и сбыта',
                'order_status_name' => 'В производстве',
                'action' => 'Завершить производство',
            ],

            'response_for_route_orders_order_items_index_is_view_orders_orders_order_items_index_with_production_completed_orders' => [
                'role_name' => 'Сотрудник отдела снабжения и сбыта',
                'order_status_name' => 'Произведен',
                'action' => 'На отгрузку',
            ],

            'response_for_route_orders_order_items_index_is_view_orders_orders_order_items_index_with_on_shipment_orders' => [
                'role_name' => 'Сотрудник отдела снабжения и сбыта',
                'order_status_name' => 'На отгрузке',
                'action' => 'Отгрузить',
            ],

            'response_for_route_orders_order_items_index_is_view_orders_orders_order_items_index_with_shipped_orders' => [
                'role_name' => 'Сотрудник экономического отдела',
                'order_status_name' => 'Отгружен',
                'action' => '',
            ],
        ];
    }

    /**
     * @test
     * @dataProvider orderStatusProvider
     * @param string $roleName
     * @param string $orderStatusName
     * @param string $action
     */
    public function order_card_viewing_as_expected($roleName, $orderStatusName, $action)
    {
        $user = User::whereHas('role', function ($query) use ($roleName) {
            $query->where('roles.name', $roleName);
        })->first();
        Sanctum::actingAs($user);

        $orderStatus = OrderStatus::where('name', $orderStatusName)->first();

        $order = Order::has('items')->where('status_id', $orderStatus->id)->first();

        $response = $this->get("/orders/{$order->id}");
        $response->assertOk();

        $response->assertViewIs('orders.orders.order-items.index');

        $response->assertSee($action);

        $response->assertSee($order->code);
        $response->assertSee($order->name);

        $response->assertSee($order->creation_date);
        $response->assertSee($order->closing_date);
        $response->assertSee($order->launch_date);
        $response->assertSee($order->completion_date);

        $response->assertSee($order->customer->name);
        $response->assertSee($order->note);

        $orderAmount = $order->amount;
        $response->assertSee($orderAmount);

        $orderCost = $order->cost;
        $response->assertSee($orderCost);

        $expectedProfit = $orderAmount - $orderCost;
        $response->assertSee($expectedProfit);

        $profitability = round($expectedProfit / $orderAmount * 100, 2);
        $response->assertSee($profitability);

        foreach ($order->items as $orderItem) {
            $response->assertSee($orderItem->drawing);
            $response->assertSee($orderItem->name);
            $response->assertSee($orderItem->pivot->cnt);
            $response->assertSee($orderItem->unit->short_name);
            $response->assertSee(20);

            $amountWithoutNDS = round($orderItem->pivot->amount - ($orderItem->pivot->amount * (20/120)), 2);
            $response->assertSee($amountWithoutNDS);

            $response->assertSee($orderItem->pivot->per_unit_price);
            $response->assertSee($orderItem->pivot->amount);
            $response->assertSee($orderItem->pivot->cost);
        }
    }

    /** @test */
    public function get_labor_intensity_and_profit_distribution_test()
    {
        $this->seed(AssemblyItemSeeder::class);

        $order = Order::create([
            'code' => '0-0',
            'name' => '0А',
            'creation_date' => '2024-01-01',
            'launch_date' => null,
            'closing_date' => '2024-12-12',
            'completion_date' => null,
            'note' => null,
            'customer_inn' => 'АВТ_П3_17',
        ]);

        $orderItemService = new ModifyService();

        $newOrderItemData = [
            'item_id' => 'Устройство',
            'per_unit_price' => 50895.43,
            'cnt' => 85,
        ];

        $orderItemService->store($order, $newOrderItemData);

        $newOrderItemData = [
            'item_id' => 'Сборочная единица',
            'per_unit_price' => 38095.43,
            'cnt' => 77,
        ];

        $orderItemService->store($order, $newOrderItemData);

        $response = $this->get("/orders/{$order->id}");
        $response->assertOk();

        //Первое изделие заказа - $otherAssemblyItem: Всего 24 точки.
        //Из них: 'Монт' - 12, '1.1' - 6, '2' - 5, '3г' - 1
        //Т.е. Заготовительный цех - 12, Механический цех - 6, Сборочный цех - 5, Гальванический цех - 1, ОТК - 0
        //Второе изделие заказа - $assemblyItem: Всего 16 точек.
        //Из них: 'Монт' - 8, '1.1' - 4, '2' - 3, '3г' - 1
        //Т.е. Заготовительный цех - 8, Механический цех - 4, Сборочный цех - 3, Гальванический цех - 1, ОТК - 0

        //Итого в заказе: Всего 40 точек.
        //Из них: 'Монт' - 20, '1.1' - 10, '2' - 8, '3г' - 2
        //Т.е. Заготовительный цех - 20, Механический цех - 10, Сборочный цех - 8, Гальванический цех - 2, ОТК - 0
        //Тогда распределение трудоемкости и прибыли:
        //Гальванический цех - (2/40)*100=5, Заготовительный цех - (20/40)*100=50, Механический цех - (10/40)*100=25, Сборочный цех - (8/40)*100=20, ОТК - 0

        $response->assertSeeInOrder([
            5, 50, 25, 20, 0
        ]);
    }
}
