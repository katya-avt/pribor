<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\ShippedOrderStatisticsFilter;
use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use App\Services\Orders\ShippedOrderStatistics\Service;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShippedOrderStatisticsFilterTest extends TestCase
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
    public function shipped_order_statistics_can_be_filtered_by_current_year_period()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $currentDate = Carbon::now();
        $currentYear = $currentDate->startOfYear();

        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentYear->copy()->addYear()->toDate(),
            'completion_date' => $currentYear->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $nextYearShippedOrder = Order::create([
            'code' => 'Код заказа, отгруженного в следующем году',
            'name' => 'Наименование заказа, отгруженного в следующем году',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentYear->copy()->addYears(2)->toDate(),
            'completion_date' => $currentYear->copy()->addYear()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $delayedShippedOrder = Order::create([
            'code' => 'Код отгруженного с нарушением срока заказа',
            'name' => 'Наименование отгруженного с нарушением срока заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentYear->copy()->subYear()->toDate(),
            'completion_date' => $currentYear->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 75000.75,
            'cost' => 25000.25,
        ]);

        $delayedShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $delayedShippedOrder->refresh();

        $data = [
            'period' => 'year',
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $service = new Service();

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        //amount-cost
        $expectedTotalProfitValue = round(25000.25 + 50000.5, 2);
        $this->assertEquals($expectedTotalProfitValue, $totalProfitValue);

        //AVG(((amount-cost)/amount) * 100)
        $expectedAverageProfitabilityValue = round((50 + 66.66667)/2, 2);
        $this->assertEquals($expectedAverageProfitabilityValue, round($averageProfitabilityValue, 2));

        $expectedOrdersShippedOutOfTimeCount = 1;
        $this->assertEquals($expectedOrdersShippedOutOfTimeCount, $ordersShippedOutOfTimeCount);

        $expectedValueGroups = collect([50, 50]);
        $this->assertEquals($expectedValueGroups, $valueGroups->flatten());

        $expectedCustomerGroups = collect(['АВТ_П3_17', 'АВТ_П3_18']);
        $this->assertEquals($expectedCustomerGroups, $customerGroups->flatten());
    }

    /** @test */
    public function shipped_order_statistics_can_be_filtered_by_current_quarter_period()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $currentDate = Carbon::now();
        $currentQuarter = $currentDate->startOfQuarter();

        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentQuarter->copy()->addQuarter()->toDate(),
            'completion_date' => $currentQuarter->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $nextQuarterShippedOrder = Order::create([
            'code' => 'Код заказа, отгруженного в следующем квартале',
            'name' => 'Наименование заказа, отгруженного в следующем квартале',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentQuarter->copy()->addQuarters(2)->toDate(),
            'completion_date' => $currentQuarter->copy()->addQuarter()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $delayedShippedOrder = Order::create([
            'code' => 'Код отгруженного с нарушением срока заказа',
            'name' => 'Наименование отгруженного с нарушением срока заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentQuarter->copy()->subQuarter()->toDate(),
            'completion_date' => $currentQuarter->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 75000.75,
            'cost' => 25000.25,
        ]);

        $delayedShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $delayedShippedOrder->refresh();

        $data = [
            'period' => 'quarter',
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $service = new Service();

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        //amount-cost
        $expectedTotalProfitValue = round(25000.25 + 50000.5, 2);
        $this->assertEquals($expectedTotalProfitValue, $totalProfitValue);

        //AVG(((amount-cost)/amount) * 100)
        $expectedAverageProfitabilityValue = round((50 + 66.66667)/2, 2);
        $this->assertEquals($expectedAverageProfitabilityValue, round($averageProfitabilityValue, 2));

        $expectedOrdersShippedOutOfTimeCount = 1;
        $this->assertEquals($expectedOrdersShippedOutOfTimeCount, $ordersShippedOutOfTimeCount);

        $expectedValueGroups = collect([50, 50]);
        $this->assertEquals($expectedValueGroups, $valueGroups->flatten());

        $expectedCustomerGroups = collect(['АВТ_П3_17', 'АВТ_П3_18']);
        $this->assertEquals($expectedCustomerGroups, $customerGroups->flatten());
    }

    /** @test */
    public function shipped_order_statistics_can_be_filtered_by_current_month_period()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $currentDate = Carbon::now();
        $currentMonth = $currentDate->startOfMonth();

        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentMonth->copy()->addMonth()->toDate(),
            'completion_date' => $currentMonth->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $nextMonthShippedOrder = Order::create([
            'code' => 'Код заказа, отгруженного в следующем месяце',
            'name' => 'Наименование заказа, отгруженного в следующем месяце',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentMonth->copy()->addMonths(2)->toDate(),
            'completion_date' => $currentMonth->copy()->addMonth()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $delayedShippedOrder = Order::create([
            'code' => 'Код отгруженного с нарушением срока заказа',
            'name' => 'Наименование отгруженного с нарушением срока заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentMonth->copy()->subMonth()->toDate(),
            'completion_date' => $currentMonth->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 75000.75,
            'cost' => 25000.25,
        ]);

        $delayedShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $delayedShippedOrder->refresh();

        $data = [
            'period' => 'month',
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $service = new Service();

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        //amount-cost
        $expectedTotalProfitValue = round(25000.25 + 50000.5, 2);
        $this->assertEquals($expectedTotalProfitValue, $totalProfitValue);

        //AVG(((amount-cost)/amount) * 100)
        $expectedAverageProfitabilityValue = round((50 + 66.66667)/2, 2);
        $this->assertEquals($expectedAverageProfitabilityValue, round($averageProfitabilityValue, 2));

        $expectedOrdersShippedOutOfTimeCount = 1;
        $this->assertEquals($expectedOrdersShippedOutOfTimeCount, $ordersShippedOutOfTimeCount);

        $expectedValueGroups = collect([50, 50]);
        $this->assertEquals($expectedValueGroups, $valueGroups->flatten());

        $expectedCustomerGroups = collect(['АВТ_П3_17', 'АВТ_П3_18']);
        $this->assertEquals($expectedCustomerGroups, $customerGroups->flatten());
    }

    /** @test */
    public function shipped_order_statistics_can_be_filtered_by_selected_quarter()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $currentDate = Carbon::now();
        $currentQuarter = $currentDate->startOfQuarter();

        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentQuarter->copy()->addQuarter()->toDate(),
            'completion_date' => $currentQuarter->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $nextQuarterShippedOrder = Order::create([
            'code' => 'Код заказа, отгруженного в следующем квартале',
            'name' => 'Наименование заказа, отгруженного в следующем квартале',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentQuarter->copy()->addQuarters(2)->toDate(),
            'completion_date' => $currentQuarter->copy()->addQuarter()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $delayedShippedOrder = Order::create([
            'code' => 'Код отгруженного с нарушением срока заказа',
            'name' => 'Наименование отгруженного с нарушением срока заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentQuarter->copy()->subQuarter()->toDate(),
            'completion_date' => $currentQuarter->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 75000.75,
            'cost' => 25000.25,
        ]);

        $delayedShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $delayedShippedOrder->refresh();

        $data = [
            'period' => null,
            'quarter' => $currentQuarter->format('Y-m'),
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $service = new Service();

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        //amount-cost
        $expectedTotalProfitValue = round(25000.25 + 50000.5, 2);
        $this->assertEquals($expectedTotalProfitValue, $totalProfitValue);

        //AVG(((amount-cost)/amount) * 100)
        $expectedAverageProfitabilityValue = round((50 + 66.66667)/2, 2);
        $this->assertEquals($expectedAverageProfitabilityValue, round($averageProfitabilityValue, 2));

        $expectedOrdersShippedOutOfTimeCount = 1;
        $this->assertEquals($expectedOrdersShippedOutOfTimeCount, $ordersShippedOutOfTimeCount);

        $expectedValueGroups = collect([50, 50]);
        $this->assertEquals($expectedValueGroups, $valueGroups->flatten());

        $expectedCustomerGroups = collect(['АВТ_П3_17', 'АВТ_П3_18']);
        $this->assertEquals($expectedCustomerGroups, $customerGroups->flatten());
    }

    /** @test */
    public function shipped_order_statistics_can_be_filtered_by_selected_month()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $currentDate = Carbon::now();
        $currentMonth = $currentDate->startOfMonth();

        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentMonth->copy()->addMonth()->toDate(),
            'completion_date' => $currentMonth->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $nextMonthShippedOrder = Order::create([
            'code' => 'Код заказа, отгруженного в следующем месяце',
            'name' => 'Наименование заказа, отгруженного в следующем месяце',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentMonth->copy()->addMonths(2)->toDate(),
            'completion_date' => $currentMonth->copy()->addMonth()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $delayedShippedOrder = Order::create([
            'code' => 'Код отгруженного с нарушением срока заказа',
            'name' => 'Наименование отгруженного с нарушением срока заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentMonth->copy()->subMonth()->toDate(),
            'completion_date' => $currentMonth->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 75000.75,
            'cost' => 25000.25,
        ]);

        $delayedShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $delayedShippedOrder->refresh();

        $data = [
            'period' => null,
            'quarter' => null,
            'month' => $currentMonth->format('Y-m'),
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $service = new Service();

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        //amount-cost
        $expectedTotalProfitValue = round(25000.25 + 50000.5, 2);
        $this->assertEquals($expectedTotalProfitValue, $totalProfitValue);

        //AVG(((amount-cost)/amount) * 100)
        $expectedAverageProfitabilityValue = round((50 + 66.66667)/2, 2);
        $this->assertEquals($expectedAverageProfitabilityValue, round($averageProfitabilityValue, 2));

        $expectedOrdersShippedOutOfTimeCount = 1;
        $this->assertEquals($expectedOrdersShippedOutOfTimeCount, $ordersShippedOutOfTimeCount);

        $expectedValueGroups = collect([50, 50]);
        $this->assertEquals($expectedValueGroups, $valueGroups->flatten());

        $expectedCustomerGroups = collect(['АВТ_П3_17', 'АВТ_П3_18']);
        $this->assertEquals($expectedCustomerGroups, $customerGroups->flatten());
    }

    /** @test */
    public function shipped_order_statistics_can_be_filtered_by_selected_date()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $currentDate = Carbon::now();

        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentDate->copy()->addDay()->toDate(),
            'completion_date' => $currentDate->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $nextDateShippedOrder = Order::create([
            'code' => 'Код заказа, отгруженного на следующий день',
            'name' => 'Наименование заказа, отгруженного на следующий день',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentDate->copy()->addDays(2)->toDate(),
            'completion_date' => $currentDate->copy()->addDay()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $delayedShippedOrder = Order::create([
            'code' => 'Код отгруженного с нарушением срока заказа',
            'name' => 'Наименование отгруженного с нарушением срока заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentDate->copy()->subDay()->toDate(),
            'completion_date' => $currentDate->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 75000.75,
            'cost' => 25000.25,
        ]);

        $delayedShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $delayedShippedOrder->refresh();

        $data = [
            'period' => null,
            'quarter' => null,
            'month' => null,
            'date' => $currentDate->format('Y-m-d'),
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $service = new Service();

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        //amount-cost
        $expectedTotalProfitValue = round(25000.25 + 50000.5, 2);
        $this->assertEquals($expectedTotalProfitValue, $totalProfitValue);

        //AVG(((amount-cost)/amount) * 100)
        $expectedAverageProfitabilityValue = round((50 + 66.66667)/2, 2);
        $this->assertEquals($expectedAverageProfitabilityValue, round($averageProfitabilityValue, 2));

        $expectedOrdersShippedOutOfTimeCount = 1;
        $this->assertEquals($expectedOrdersShippedOutOfTimeCount, $ordersShippedOutOfTimeCount);

        $expectedValueGroups = collect([50, 50]);
        $this->assertEquals($expectedValueGroups, $valueGroups->flatten());

        $expectedCustomerGroups = collect(['АВТ_П3_17', 'АВТ_П3_18']);
        $this->assertEquals($expectedCustomerGroups, $customerGroups->flatten());
    }

    /** @test */
    public function shipped_order_statistics_can_be_filtered_by_selected_date_interval()
    {
        Order::query()->update(['status_id' => OrderStatus::ON_SHIPMENT]);

        $currentDate = Carbon::now();

        $shippedOrder = Order::create([
            'code' => 'Код заказа',
            'name' => 'Наименование заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentDate->copy()->addDay()->toDate(),
            'completion_date' => $currentDate->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $nextDateShippedOrder = Order::create([
            'code' => 'Код заказа, отгруженного на следующий день',
            'name' => 'Наименование заказа, отгруженного на следующий день',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentDate->copy()->addDays(2)->toDate(),
            'completion_date' => $currentDate->copy()->addDay()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_17',
            'amount' => 50000.50,
            'cost' => 25000.25,
        ]);

        $shippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $shippedOrder->refresh();

        $delayedShippedOrder = Order::create([
            'code' => 'Код отгруженного с нарушением срока заказа',
            'name' => 'Наименование отгруженного с нарушением срока заказа',
            'launch_date' => '2024-01-01',
            'closing_date' => $currentDate->copy()->subDay()->toDate(),
            'completion_date' => $currentDate->copy()->toDate(),
            'note' => 'Примечание',
            'customer_inn' => 'АВТ_П3_18',
            'amount' => 75000.75,
            'cost' => 25000.25,
        ]);

        $delayedShippedOrder->update([
            'status_id' => OrderStatus::SHIPPED
        ]);
        $delayedShippedOrder->refresh();

        $data = [
            'period' => null,
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => $currentDate->format('Y-m-d'),
            'to_date' => $currentDate->copy()->addDay()->format('Y-m-d')
        ];

        $filter = app()->make(ShippedOrderStatisticsFilter::class, ['queryParams' => array_filter($data)]);

        $service = new Service();

        $totalProfitValue = $service->getTotalProfitValue()->filter($filter)->value($service::TOTAL_PROFIT);
        $averageProfitabilityValue = $service->getAverageProfitabilityValue()->filter($filter)->value($service::AVERAGE_PROFITABILITY);
        $ordersShippedOutOfTimeCount = $service->getOrdersShippedOutOfTimeCount()->filter($filter)->value($service::ORDERS_SHIPPED_OUT_OF_TIME_COUNT);

        $filteredTotalOrdersCount = $service->getTotalOrdersCount()->filter($filter)->value($service::TOTAL_ORDERS_COUNT);
        $customerDistribution = $service->getCustomerDistribution($filteredTotalOrdersCount)->filter($filter)->get();

        $valueGroups = $customerDistribution->pluck('distribution')->chunk(5);
        $customerGroups = $customerDistribution->pluck('name')->chunk(5);

        //amount-cost
        $expectedTotalProfitValue = round(25000.25 + 50000.5, 2);
        $this->assertEquals($expectedTotalProfitValue, $totalProfitValue);

        //AVG(((amount-cost)/amount) * 100)
        $expectedAverageProfitabilityValue = round((50 + 66.66667)/2, 2);
        $this->assertEquals($expectedAverageProfitabilityValue, round($averageProfitabilityValue, 2));

        $expectedOrdersShippedOutOfTimeCount = 1;
        $this->assertEquals($expectedOrdersShippedOutOfTimeCount, $ordersShippedOutOfTimeCount);

        $expectedValueGroups = collect([50, 50]);
        $this->assertEquals($expectedValueGroups, $valueGroups->flatten());

        $expectedCustomerGroups = collect(['АВТ_П3_17', 'АВТ_П3_18']);
        $this->assertEquals($expectedCustomerGroups, $customerGroups->flatten());
    }
}
