<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\OrderFilter;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Orders\OrderStatus;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderFilterTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function filterProvider()
    {
        return [
            'orders_can_be_filtered_by_code_or_name' => [
                'data' => [
                    'search' => 'заказ',
                    'customer_inn' => null,
                    'drawing' => null,
                    'sort_by' => null,
                    'sort_direction' => null
                ],
                'includedOrder' => 'Код заказа',
                'excludedOrder' => '0-0'
            ],

            'orders_can_be_filtered_by_customer' => [
                'data' => [
                    'search' => null,
                    'customer_inn' => 'АВТ_П3_17',
                    'drawing' => null,
                    'sort_by' => null,
                    'sort_direction' => null
                ],
                'includedOrder' => 'Код заказа',
                'excludedOrder' => '0-0'
            ],

            'orders_can_be_filtered_by_contained_item' => [
                'data' => [
                    'search' => null,
                    'customer_inn' => null,
                    'drawing' => 'Деталь',
                    'sort_by' => null,
                    'sort_direction' => null
                ],
                'includedOrder' => '0-0',
                'excludedOrder' => 'Код заказа'
            ],
        ];
    }

    /**
     * @test
     * @dataProvider filterProvider
     * @param array $mockedFilterData
     * @param string $includedOrder
     * @param string $excludedOrder
     */
    public function filter_results_as_expected($mockedFilterData, $includedOrder, $excludedOrder)
    {
        $firstShippedOrder = Order::create([
           'code' => 'Код заказа',
           'name' => 'Наименование заказа',
           'closing_date' => '2025-12-01',
           'note' => 'Примечание',
           'customer_inn' => 'АВТ_П3_17'
        ]);

        $secondShippedOrder = Order::create([
            'code' => '0-0',
            'name' => '0А',
            'closing_date' => '2026-12-01',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18'
        ]);

        $detailItem = Item::factory()->create([
            'drawing' => 'Деталь',
            'name' => 'Деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад готовой продукции',
            'manufacture_type_id' => 'Под заказ'
        ]);

        OrderItem::create([
            'order_id' => $secondShippedOrder->id,
            'item_id' => $detailItem->id,
            'per_unit_price' => 5000,
            'cnt' => 100
        ]);

        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($mockedFilterData)]);

        $orders = Order::with('customer')
            ->where('status_id', OrderStatus::PENDING)
            ->filter($filter)->get();

        $includedOrder = Order::where('code', $includedOrder)->first();
        $excludedOrder = Order::where('code', $excludedOrder)->first();

        $this->assertTrue($orders->contains($includedOrder));
        $this->assertFalse($orders->contains($excludedOrder));
    }

    /** @test */
    public function orders_can_be_sorted_by_creation_date()
    {
        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'closing_date' => '2024-12-01',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17'
        ]);

        $shippedOrder->update([
            'creation_date' => '1000-01-01',
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $data = [
            'search' => null,
            'customer_inn' => null,
            'drawing' => null,
            'sort_by' => 'creation_date',
            'sort_direction' => 'asc'
        ];

        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        $orders = Order::with('customer')
            ->where('status_id', OrderStatus::SHIPPED)
            ->filter($filter)->get();

        $this->assertEquals($shippedOrder->code, $orders->first()->code);
    }

    /** @test */
    public function orders_can_be_sorted_by_launch_date()
    {
        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '1000-01-01',
            'closing_date' => '2024-12-01',
            'completion_date' => '2024-10-01',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17'
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $data = [
            'search' => null,
            'customer_inn' => null,
            'drawing' => null,
            'sort_by' => 'launch_date',
            'sort_direction' => 'asc'
        ];

        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        $orders = Order::with('customer')
            ->where('status_id', OrderStatus::SHIPPED)
            ->filter($filter)->get();

        $this->assertEquals($shippedOrder->code, $orders->first()->code);
    }

    /** @test */
    public function orders_can_be_sorted_by_closing_date()
    {
        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => '3000-12-01',
            'completion_date' => '2024-10-01',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17'
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $data = [
            'search' => null,
            'customer_inn' => null,
            'drawing' => null,
            'sort_by' => 'closing_date',
            'sort_direction' => 'desc'
        ];

        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        $orders = Order::with('customer')
            ->where('status_id', OrderStatus::SHIPPED)
            ->filter($filter)->get();

        $this->assertEquals($shippedOrder->code, $orders->first()->code);
    }

    /** @test */
    public function orders_can_be_sorted_by_completion_date()
    {
        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => '2024-12-01',
            'completion_date' => '3000-10-01',
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17'
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $data = [
            'search' => null,
            'customer_inn' => null,
            'drawing' => null,
            'sort_by' => 'completion_date',
            'sort_direction' => 'desc'
        ];

        $filter = app()->make(OrderFilter::class, ['queryParams' => array_filter($data)]);

        $orders = Order::with('customer')
            ->where('status_id', OrderStatus::SHIPPED)
            ->filter($filter)->get();

        $this->assertEquals($shippedOrder->code, $orders->first()->code);
    }
}
