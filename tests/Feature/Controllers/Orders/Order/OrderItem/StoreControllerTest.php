<?php

namespace Tests\Feature\Controllers\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Orders\OrderItemRoute;
use App\Models\Orders\OrderItemSpecification;
use App\Models\Range\Cover;
use App\Models\Range\Item;
use App\Models\Range\Route;
use App\Models\Range\Specification;
use App\Models\Users\Role;
use App\Models\Users\User;
use Database\Seeders\SeedersForTests\AssemblyItemSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class StoreControllerTest extends TestCase
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
    public function an_order_item_can_be_stored_when_it_has_manufacturing_process()
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

        $data = [
            'item_id' => 'Устройство',
            'per_unit_price' => 50895.43,
            'cnt' => 85,
        ];

        $response = $this->post("/orders/{$order->id}", $data);
        $order->refresh();

        $orderItemData = $data;
        $orderItem = Item::getItemByDrawing($data['item_id']);
        $orderItemData['item_id'] = $orderItem->id;
        $orderItemData['amount'] = $data['per_unit_price'] * $data['cnt'];

        $this->assertDatabaseHas('order_item', $orderItemData);

        //Сборочная единица $assemblyItem из спецификации состава изделия $orderItem
        $assemblyItem = Item::getItemByDrawing('Сборочная единица');

        $orderItemSpecification = Specification::find($orderItem->specification_number);

        $assemblyItemCnt = $orderItemSpecification->items()
            ->where('item_id', $assemblyItem->id)->first()->pivot->cnt;


        $orderItemSpecificationData = [
            'order_item_specification_id' => "{$order->id}-{$orderItem->id}-{$orderItem->id}-{$assemblyItem->id}",
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $orderItem->id,
            'current_item_parent_id' => null,
            'current_specification_number' => $orderItem->specification_number,
            'current_cover_number' => $orderItem->cover_number,
            'current_number' => $orderItem->specification_number,
            'component_id' => $assemblyItem->id,
            'order_item_specification_parent_id' => null,
            'component_cnt' => round($data['cnt'] * $assemblyItemCnt, 2),
            'component_purchase_price' => null,
            'total_component_purchase_price' => round($data['cnt'] * $assemblyItemCnt, 2)
        ];

        $this->assertDatabaseHas('order_item_specification', $orderItemSpecificationData);

        //Металлическая деталь $detailItem из спецификации состава изделия $assemblyItem
        $detailItem = Item::getItemByDrawing('Металлическая деталь');

        $assemblyItemSpecification = Specification::find($assemblyItem->specification_number);

        $detailItemCnt = $assemblyItemSpecification->items()
            ->where('item_id', $detailItem->id)->first()->pivot->cnt;


        $orderItemSpecificationData = [
            'order_item_specification_id' => "{$order->id}-{$orderItem->id}-{$assemblyItem->id}-{$orderItem->id}-{$detailItem->id}",
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $assemblyItem->id,
            'current_item_parent_id' => $orderItem->id,
            'current_specification_number' => $assemblyItem->specification_number,
            'current_cover_number' => $assemblyItem->cover_number,
            'current_number' => $assemblyItem->specification_number,
            'component_id' => $detailItem->id,
            'order_item_specification_parent_id' => "{$order->id}-{$orderItem->id}-{$orderItem->id}-{$assemblyItem->id}",
            'component_cnt' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt, 2),
            'component_purchase_price' => null,
            'total_component_purchase_price' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt, 2)
        ];

        $this->assertDatabaseHas('order_item_specification', $orderItemSpecificationData);

        //Металл $metalItem из спецификации состава изделия $detailItem
        $metalItem = Item::getItemByDrawing('Металл');

        $detailItemSpecification = Specification::find($detailItem->specification_number);

        $metalItemCnt = $detailItemSpecification->items()
            ->where('item_id', $metalItem->id)->first()->pivot->cnt;

        $orderItemSpecificationData = [
            'order_item_specification_id' => "{$order->id}-{$orderItem->id}-{$detailItem->id}-{$assemblyItem->id}-{$metalItem->id}",
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $detailItem->id,
            'current_item_parent_id' => $assemblyItem->id,
            'current_specification_number' => $detailItem->specification_number,
            'current_cover_number' => $detailItem->cover_number,
            'current_number' => $detailItem->specification_number,
            'component_id' => $metalItem->id,
            'order_item_specification_parent_id' => "{$order->id}-{$orderItem->id}-{$assemblyItem->id}-{$orderItem->id}-{$detailItem->id}",
            'component_cnt' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt * $metalItemCnt, 2),
            'component_purchase_price' => round($metalItem->purchasedItem->purchase_price, 2),
            'total_component_purchase_price' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt * $metalItemCnt * $metalItem->purchasedItem->purchase_price, 2)
        ];

        $this->assertDatabaseHas('order_item_specification', $orderItemSpecificationData);

        //Гальваническое покрытие $galvanicItem из спецификации покрытия изделия $detailItem
        $galvanicItem = Item::getItemByDrawing('Гальваническое покрытие');

        $detailItemCover = Cover::find($detailItem->cover_number);

        $galvanicItemInCover = $detailItemCover->items()
            ->where('item_id', $galvanicItem->id)->first()->pivot;

        $galvanicItemCnt = $galvanicItemInCover->area * $galvanicItemInCover->consumption;

        $orderItemSpecificationData = [
            'order_item_specification_id' => "{$order->id}-{$orderItem->id}-{$detailItem->id}-{$assemblyItem->id}-{$galvanicItem->id}",
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $detailItem->id,
            'current_item_parent_id' => $assemblyItem->id,
            'current_specification_number' => $detailItem->specification_number,
            'current_cover_number' => $detailItem->cover_number,
            'current_number' => $detailItem->cover_number,
            'component_id' => $galvanicItem->id,
            'order_item_specification_parent_id' => "{$order->id}-{$orderItem->id}-{$assemblyItem->id}-{$orderItem->id}-{$detailItem->id}",
            'component_cnt' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt * $galvanicItemCnt, 2),
            'component_purchase_price' => null,
            'total_component_purchase_price' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt * $galvanicItemCnt, 2)
        ];

        $this->assertDatabaseHas('order_item_specification', $orderItemSpecificationData);

        //Далее аналогично. Таких записей должно быть 18 (см. AssemblyItemSeeder).
        $this->assertEquals(18, OrderItemSpecification::where('order_id', $order->id)
            ->where('item_id', $orderItem->id)->count());

        //$orderItem
        $orderItemRoute = Route::find($orderItem->route_number);
        $orderItemRoutePoint = $orderItemRoute->points()->first();

        $orderItemRouteData = [
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $orderItem->id,
            'route_number' => $orderItemRoute->number,
            'point_number' => $orderItemRoutePoint->pivot->point_number,
            'cnt' => round($data['cnt'], 2),
            'point_code' => $orderItemRoutePoint->pivot->point_code,
            'operation_code' => $orderItemRoutePoint->pivot->operation_code,
            'rate_code' => $orderItemRoutePoint->pivot->rate_code,
            'unit_time' => $orderItemRoutePoint->pivot->unit_time,
            'working_time' => $orderItemRoutePoint->pivot->working_time,
            'lead_time' => $orderItemRoutePoint->pivot->lead_time,
            'base_payment' => $orderItemRoutePoint->base_payment
        ];

        $this->assertDatabaseHas('order_item_route', $orderItemRouteData);

        //Сборочная единица $assemblyItem из спецификации состава изделия $orderItem
        $assemblyItemRoute = Route::find($assemblyItem->route_number);
        $assemblyItemRoutePoint = $assemblyItemRoute->points()->first();

        $orderItemRouteData = [
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $assemblyItem->id,
            'route_number' => $assemblyItemRoute->number,
            'point_number' => $assemblyItemRoutePoint->pivot->point_number,
            'cnt' => round($data['cnt'] * $assemblyItemCnt, 2),
            'point_code' => $assemblyItemRoutePoint->pivot->point_code,
            'operation_code' => $assemblyItemRoutePoint->pivot->operation_code,
            'rate_code' => $assemblyItemRoutePoint->pivot->rate_code,
            'unit_time' => $assemblyItemRoutePoint->pivot->unit_time,
            'working_time' => $assemblyItemRoutePoint->pivot->working_time,
            'lead_time' => $assemblyItemRoutePoint->pivot->lead_time,
            'base_payment' => $assemblyItemRoutePoint->base_payment
        ];

        $this->assertDatabaseHas('order_item_route', $orderItemRouteData);

        //Металлическая деталь $detailItem из спецификации состава изделия $assemblyItem
        $detailItemRoute = Route::find($detailItem->route_number);
        $detailItemRoutePoint = $detailItemRoute->points()->first();

        $orderItemRouteData = [
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $detailItem->id,
            'route_number' => $detailItemRoute->number,
            'point_number' => $detailItemRoutePoint->pivot->point_number,
            'cnt' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt, 2),
            'point_code' => $detailItemRoutePoint->pivot->point_code,
            'operation_code' => $detailItemRoutePoint->pivot->operation_code,
            'rate_code' => $detailItemRoutePoint->pivot->rate_code,
            'unit_time' => $detailItemRoutePoint->pivot->unit_time,
            'working_time' => $detailItemRoutePoint->pivot->working_time,
            'lead_time' => $detailItemRoutePoint->pivot->lead_time,
            'base_payment' => $detailItemRoutePoint->base_payment
        ];

        $this->assertDatabaseHas('order_item_route', $orderItemRouteData);

        //Гальваническое покрытие $galvanicItem из спецификации покрытия изделия $detailItem
        $galvanicItemRoute = Route::find($galvanicItem->route_number);
        $galvanicItemRoutePoint = $galvanicItemRoute->points()->first();

        $orderItemRouteData = [
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $galvanicItem->id,
            'route_number' => $galvanicItemRoute->number,
            'point_number' => $galvanicItemRoutePoint->pivot->point_number,
            'cnt' => round($data['cnt'] * $assemblyItemCnt * $detailItemCnt * $galvanicItemCnt, 2),
            'point_code' => $galvanicItemRoutePoint->pivot->point_code,
            'operation_code' => $galvanicItemRoutePoint->pivot->operation_code,
            'rate_code' => $galvanicItemRoutePoint->pivot->rate_code,
            'unit_time' => $galvanicItemRoutePoint->pivot->unit_time,
            'working_time' => $galvanicItemRoutePoint->pivot->working_time,
            'lead_time' => $galvanicItemRoutePoint->pivot->lead_time,
            'base_payment' => $galvanicItemRoutePoint->base_payment
        ];

        $this->assertDatabaseHas('order_item_route', $orderItemRouteData);

        //Далее аналогично. Таких записей должно быть 7*4=28 (см. AssemblyItemSeeder).
        $this->assertEquals(28, OrderItemRoute::where('order_id', $order->id)
            ->where('item_id', $orderItem->id)->count());

        $this->assertNotNull($orderItem->added_to_order_at);
        $this->assertNotNull($orderItemSpecification->added_to_order_at);
        $this->assertNotNull($orderItemRoute->added_to_order_at);

        $this->assertNotNull($detailItem->added_to_order_at);
        $this->assertNotNull($detailItemSpecification->added_to_order_at);
        $this->assertNotNull($detailItemCover->added_to_order_at);
        $this->assertNotNull($detailItemRoute->added_to_order_at);

        $this->assertNotNull($galvanicItem->added_to_order_at);
        $this->assertNotNull($galvanicItemRoute->added_to_order_at);

        $response->assertRedirect("/orders/{$order->id}");

        $response = $this->get("/orders/{$order->id}");
        $response->assertSee(__('messages.successful_store'));
    }

    /** @test */
    public function an_order_item_can_be_stored_when_it_is_a_purchased_item_without_manufacturing_process()
    {
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

        $orderItem = Item::has('purchasedItem')->whereNull('specification_number')
            ->whereNull('cover_number')->whereNull('route_number')->first();

        $orderItemPurchasePrice = $orderItem->purchasedItem->purchase_price;

        $data = [
            'item_id' => $orderItem->drawing,
            'per_unit_price' => 50895.43,
            'cnt' => 85,
        ];

        $response = $this->post("/orders/{$order->id}", $data);
        $order->refresh();

        $orderItemData = $data;
        $orderItemData['item_id'] = $orderItem->id;
        $orderItemData['amount'] = round($data['per_unit_price'] * $data['cnt'], 2);
        $orderItemData['cost'] = round($orderItemPurchasePrice * $data['cnt'], 2);

        $this->assertDatabaseHas('order_item', $orderItemData);

        $orderItemSpecification = OrderItemSpecification::where('order_id', $order->id)->get();
        $this->assertTrue($orderItemSpecification->isEmpty());

        $orderItemRoute = OrderItemRoute::where('order_id', $order->id)->get();
        $this->assertTrue($orderItemRoute->isEmpty());

        $this->assertEquals($orderItemData['amount'], $order->amount);
        $this->assertEquals($orderItemData['cost'], $order->cost);
    }

    /** @test */
    public function an_order_item_can_be_stored_when_it_is_a_purchased_item_with_manufacturing_process()
    {
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

        $orderItem = Item::where('drawing', 'like', "%РАД%")->first();

        $cover = Cover::whereHas('items', function ($query) {
            $query->where('items.name', 'like', '%Краска%');
        })->first();

        $route = Route::has('points')->first();

        $orderItem->update([
            'cover_number' => $cover->number,
            'route_number' => $route->number
        ]);

        $data = [
            'item_id' => $orderItem->drawing,
            'per_unit_price' => 50895.43,
            'cnt' => 85,
        ];

        $response = $this->post("/orders/{$order->id}", $data);
        $order->refresh();

        $orderItemData = $data;
        $orderItemData['item_id'] = $orderItem->id;
        $orderItemData['amount'] = round($data['per_unit_price'] * $data['cnt'], 2);

        $this->assertDatabaseHas('order_item', $orderItemData);

        $coverItem = $cover->items->first();

        $coverItemCnt = $coverItem->pivot->area * $coverItem->pivot->consumption;

        $coverItemPurchasePrice = $coverItem->purchasedItem->purchase_price;

        $orderItemSpecificationData = [
            'order_item_specification_id' => "{$order->id}-{$orderItem->id}-{$orderItem->id}-{$coverItem->id}",
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $orderItem->id,
            'current_item_parent_id' => null,
            'current_specification_number' => null,
            'current_cover_number' => $orderItem->cover_number,
            'current_number' => $orderItem->cover_number,
            'component_id' => $coverItem->id,
            'order_item_specification_parent_id' => null,
            'component_cnt' => round($data['cnt'] * $coverItemCnt, 2),
            'component_purchase_price' => $coverItemPurchasePrice,
            'total_component_purchase_price' => round($data['cnt'] * $coverItemCnt * $coverItemPurchasePrice, 2)
        ];

        $this->assertDatabaseHas('order_item_specification', $orderItemSpecificationData);

        $orderItemRoutePoint = $route->points()->first();

        $orderItemRouteData = [
            'order_id' => $order->id,
            'item_id' => $orderItem->id,
            'current_item_id' => $orderItem->id,
            'route_number' => $route->number,
            'point_number' => $orderItemRoutePoint->pivot->point_number,
            'cnt' => round($data['cnt'], 2),
            'point_code' => $orderItemRoutePoint->pivot->point_code,
            'operation_code' => $orderItemRoutePoint->pivot->operation_code,
            'rate_code' => $orderItemRoutePoint->pivot->rate_code,
            'unit_time' => $orderItemRoutePoint->pivot->unit_time,
            'working_time' => $orderItemRoutePoint->pivot->working_time,
            'lead_time' => $orderItemRoutePoint->pivot->lead_time,
            'base_payment' => $orderItemRoutePoint->base_payment
        ];

        $this->assertDatabaseHas('order_item_route', $orderItemRouteData);

        $this->assertEquals($orderItemData['amount'], $order->amount);
    }
}
