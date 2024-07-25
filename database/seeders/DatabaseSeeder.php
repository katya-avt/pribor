<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Orders\Order;
use App\Models\Orders\OrderItem;
use App\Models\Orders\OrderStatus;
use App\Models\Range\Cover;
use App\Models\Range\CoverItem;
use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\ItemType;
use App\Models\Range\MainWarehouse;
use App\Models\Range\ManufactureType;
use App\Models\Range\PointBasePaymentHistory;
use App\Models\Range\PurchasedItemPurchasePriceHistory;
use App\Models\Range\Route;
use App\Models\Range\RoutePoint;
use App\Models\Range\SpecificationItem;
use App\Models\Range\Unit;
use App\Services\Orders\Order\OrderItem\ModifyService;
use Database\Seeders\Orders\CustomerSeeder;
use Database\Seeders\Orders\OrderStatusSeeder;
use Database\Seeders\Range\DepartmentSeeder;
use Database\Seeders\Range\GroupSeeder;
use Database\Seeders\Range\ItemTypeSeeder;
use Database\Seeders\Range\MainWarehouseSeeder;
use Database\Seeders\Range\ManufactureTypeSeeder;
use Database\Seeders\Range\OperationSeeder;
use Database\Seeders\Range\PointSeeder;
use Database\Seeders\Range\RateSeeder;
use Database\Seeders\Range\UnitSeeder;
use Database\Seeders\Users\PermissionSeeder;
use Database\Seeders\Users\RolePermissionSeeder;
use Database\Seeders\Users\RoleSeeder;
use Database\Seeders\Users\UserSeeder;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ItemTypeSeeder::class,
            GroupSeeder::class,
            UnitSeeder::class,
            MainWarehouseSeeder::class,
            ManufactureTypeSeeder::class,
            DepartmentSeeder::class,
            PointSeeder::class,
            OperationSeeder::class,
            RateSeeder::class,
            CustomerSeeder::class,
            OrderStatusSeeder::class,
            RoleSeeder::class,
            PermissionSeeder::class,
            RolePermissionSeeder::class,
            UserSeeder::class,
        ]);

        $numberOfItems = 25;
        $maxPointNumber = 4;
        $detailItems = collect();
        $metalItems = collect();
        $galvanicItems = collect();
        $chemicalItems = collect();
        $plasticDetailItems = collect();
        $plasticItems = collect();
        $paintItems = collect();
        $assemblyItems = collect();
        $fastenerItems = collect();
        $otherAssemblyItems = collect();
        $variousItems = collect();
        $cableItems = collect();
        $tollingDetails = collect();

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $detailItem = Item::factory()->create([
                'drawing' => 'РАМГ.735211.001' . '_' . $i,
                'name' => 'Деталь' . '_' . $i,
                'item_type_id' => ItemType::PROPRIETARY,
                'group_id' => Group::DETAIL,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => '00060667' . $i,
                'cover_number' => '00060668' . $i,
                'route_number' => '000039998' . $i
            ]);

            $detailItems->push($detailItem);
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $metalItem = Item::factory()->create([
                'drawing' => 'Плита_Д16' . '_' . $i,
                'name' => 'Плита Д16 ГОСТ 12345-11' . '_' . $i,
                'item_type_id' => ItemType::PURCHASED,
                'group_id' => Group::METAL,
                'unit_code' => Unit::KG,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => null,
                'route_number' => null
            ]);

            $metalItems->push($metalItem);
        }

        foreach ($detailItems->zip($metalItems) as $detailMetal) {
            $detail = $detailMetal[0];
            $metal = $detailMetal[1];

            SpecificationItem::factory()->create([
                'specification_number' => $detail->specification_number,
                'item_id' => $metal->id
            ]);
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $galvanicItem = Item::factory()->create([
                'drawing' => 'Ан.окс.нв.' . '_' . $i,
                'name' => 'Анодное оксидирование наполнен.водой' . '_' . $i,
                'item_type_id' => ItemType::PROPRIETARY,
                'group_id' => Group::GALVANIC,
                'unit_code' => Unit::L,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => '00050556' . $i,
                'cover_number' => null,
                'route_number' => '000037778' . $i
            ]);

            $galvanicItems->push($galvanicItem);
        }

        foreach ($detailItems->zip($galvanicItems) as $detailGalvanic) {
            $detail = $detailGalvanic[0];
            $galvanic = $detailGalvanic[1];

            CoverItem::factory()->create([
                'cover_number' => $detail->cover_number,
                'item_id' => $galvanic->id
            ]);
        }

        foreach ($detailItems as $detailItem) {
            for ($i = 1; $i <= $maxPointNumber; $i++) {
                RoutePoint::factory()->create([
                    'route_number' => $detailItem->route_number,
                    'point_number' => $i
                ]);
            }
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $chemicalItem = Item::factory()->create([
                'drawing' => 'DEOX_123.456' . '_' . $i,
                'name' => 'DEOX химикат' . '_' . $i,
                'item_type_id' => ItemType::PURCHASED,
                'group_id' => Group::CHEMICAL,
                'unit_code' => Unit::L,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => null,
                'route_number' => null
            ]);

            $chemicalItems->push($chemicalItem);
        }

        foreach ($galvanicItems->zip($chemicalItems) as $galvanicChemical) {
            $galvanic = $galvanicChemical[0];
            $chemical = $galvanicChemical[1];

            SpecificationItem::factory()->create([
                'specification_number' => $galvanic->specification_number,
                'item_id' => $chemical->id
            ]);
        }

        foreach ($galvanicItems as $galvanicItem) {
            for ($i = 1; $i <= $maxPointNumber; $i++) {
                RoutePoint::factory()->create([
                    'route_number' => $galvanicItem->route_number,
                    'point_number' => $i
                ]);
            }
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $plasticDetailItem = Item::factory()->create([
                'drawing' => 'ПЛАСТ.123456.001' . '_' . $i,
                'name' => 'Деталь_ПЛАСТ' . '_' . $i,
                'item_type_id' => ItemType::PROPRIETARY,
                'group_id' => Group::DETAIL,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => '00040443' . $i,
                'cover_number' => '00040446' . $i,
                'route_number' => '000036668' . $i
            ]);

            $plasticDetailItems->push($plasticDetailItem);
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $plasticItem = Item::factory()->create([
                'drawing' => 'PP_123.456' . '_' . $i,
                'name' => 'PP_123.456 ГОСТ 12345-12' . '_' . $i,
                'item_type_id' => ItemType::PURCHASED,
                'group_id' => Group::PLASTIC,
                'unit_code' => Unit::KG,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => null,
                'route_number' => null
            ]);

            $plasticItems->push($plasticItem);
        }

        foreach ($plasticDetailItems->zip($plasticItems) as $detailPlastic) {
            $detail = $detailPlastic[0];
            $plastic = $detailPlastic[1];

            SpecificationItem::factory()->create([
                'specification_number' => $detail->specification_number,
                'item_id' => $plastic->id
            ]);
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $paintItem = Item::factory()->create([
                'drawing' => 'КР_001.234' . '_' . $i,
                'name' => 'Краска' . '_' . $i,
                'item_type_id' => ItemType::PURCHASED,
                'group_id' => Group::PAINT,
                'unit_code' => Unit::L,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => null,
                'route_number' => null
            ]);

            $paintItems->push($paintItem);
        }

        foreach ($plasticDetailItems->zip($paintItems) as $detailPaint) {
            $detail = $detailPaint[0];
            $paint = $detailPaint[1];

            CoverItem::factory()->create([
                'cover_number' => $detail->cover_number,
                'item_id' => $paint->id
            ]);
        }

        foreach ($plasticDetailItems as $plasticDetailItem) {
            for ($i = 1; $i <= $maxPointNumber; $i++) {
                RoutePoint::factory()->create([
                    'route_number' => $plasticDetailItem->route_number,
                    'point_number' => $i
                ]);
            }
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $assemblyItem = Item::factory()->create([
                'drawing' => 'АБВГ_123456.001' . '_' . $i,
                'name' => 'Сборочная единица' . '_' . $i,
                'item_type_id' => ItemType::PROPRIETARY,
                'group_id' => Group::ASSEMBLY_ITEM,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => '00030335' . $i,
                'cover_number' => '00030338' . $i,
                'route_number' => '000035558' . $i
            ]);

            $assemblyItems->push($assemblyItem);
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $fastenerItem = Item::factory()->create([
                'drawing' => 'Болт_10*20_DIN_7798' . '_' . $i,
                'name' => 'Болт 10*20 DIN 7798' . '_' . $i,
                'item_type_id' => ItemType::PURCHASED,
                'group_id' => Group::FASTENER,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => null,
                'route_number' => null
            ]);

            $fastenerItems->push($fastenerItem);
        }

        foreach ($assemblyItems->zip($fastenerItems) as $assemblyFastener) {
            $assembly = $assemblyFastener[0];
            $fastener = $assemblyFastener[1];

            $detailForAssemblyItem = $detailItems->random();
            $plasticDetailForAssemblyItem = $plasticDetailItems->random();

            SpecificationItem::factory()->create([
                'specification_number' => $assembly->specification_number,
                'item_id' => $detailForAssemblyItem->id
            ]);

            SpecificationItem::factory()->create([
                'specification_number' => $assembly->specification_number,
                'item_id' => $plasticDetailForAssemblyItem->id
            ]);

            SpecificationItem::factory()->create([
                'specification_number' => $assembly->specification_number,
                'item_id' => $fastener->id
            ]);
        }

        foreach ($assemblyItems as $assemblyItem) {
            $paintForAssemblyItem = $paintItems->random();

            CoverItem::factory()->create([
                'cover_number' => $assemblyItem->cover_number,
                'item_id' => $paintForAssemblyItem->id
            ]);
        }

        foreach ($assemblyItems as $assemblyItem) {
            for ($i = 1; $i <= $maxPointNumber; $i++) {
                RoutePoint::factory()->create([
                    'route_number' => $assemblyItem->route_number,
                    'point_number' => $i
                ]);
            }
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $otherAssemblyItem = Item::factory()->create([
                'drawing' => 'ДЗЕИ.123.456' . '_' . $i,
                'name' => 'Устройство' . '_' . $i,
                'item_type_id' => ItemType::PROPRIETARY,
                'group_id' => Group::ASSEMBLY_ITEM,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => '00020224' . $i,
                'cover_number' => '00020227' . $i,
                'route_number' => '000034448' . $i
            ]);

            $otherAssemblyItems->push($otherAssemblyItem);
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $variousItem = Item::factory()->create([
                'drawing' => 'РАД.123_456' . '_' . $i,
                'name' => 'Радиатор' . '_' . $i,
                'item_type_id' => ItemType::PURCHASED,
                'group_id' => Group::VARIOUS,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => null,
                'route_number' => null
            ]);

            $variousItems->push($variousItem);
        }

        for ($i = 1; $i <= $numberOfItems; $i++) {
            $cableItem = Item::factory()->create([
                'drawing' => 'КАБ.123_456' . '_' . $i,
                'name' => 'Кабель' . '_' . $i,
                'item_type_id' => ItemType::PURCHASED,
                'group_id' => Group::CABLE_ITEM,
                'unit_code' => Unit::M,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => null,
                'route_number' => null
            ]);

            $cableItems->push($cableItem);
        }

        foreach ($otherAssemblyItems as $otherAssemblyItem) {
            $assemblyItemForOtherAssemblyItem = $assemblyItems->random();
            $detailForOtherAssemblyItem = $detailItems->random();
            $variousItemForOtherAssemblyItem = $variousItems->random();
            $cableItemForOtherAssemblyItem = $cableItems->random();
            $fastenerForOtherAssemblyItem = $fastenerItems->random();

            SpecificationItem::factory()->create([
                'specification_number' => $otherAssemblyItem->specification_number,
                'item_id' => $assemblyItemForOtherAssemblyItem->id
            ]);

            SpecificationItem::factory()->create([
                'specification_number' => $otherAssemblyItem->specification_number,
                'item_id' => $detailForOtherAssemblyItem->id
            ]);

            SpecificationItem::factory()->create([
                'specification_number' => $otherAssemblyItem->specification_number,
                'item_id' => $variousItemForOtherAssemblyItem->id
            ]);

            SpecificationItem::factory()->create([
                'specification_number' => $otherAssemblyItem->specification_number,
                'item_id' => $cableItemForOtherAssemblyItem->id
            ]);

            SpecificationItem::factory()->create([
                'specification_number' => $otherAssemblyItem->specification_number,
                'item_id' => $fastenerForOtherAssemblyItem->id
            ]);
        }

        foreach ($otherAssemblyItems as $otherAssemblyItem) {
            $paintForOtherAssemblyItem = $paintItems->random();

            CoverItem::factory()->create([
                'cover_number' => $otherAssemblyItem->cover_number,
                'item_id' => $paintForOtherAssemblyItem->id
            ]);
        }

        foreach ($otherAssemblyItems as $otherAssemblyItem) {
            for ($i = 1; $i <= $maxPointNumber; $i++) {
                RoutePoint::factory()->create([
                    'route_number' => $otherAssemblyItem->route_number,
                    'point_number' => $i
                ]);
            }
        }

        PurchasedItemPurchasePriceHistory::factory(1500)->create();
        PointBasePaymentHistory::factory(500)->create();

        $orders = Order::factory(65)->create();
        $orderItemService = new ModifyService();

        foreach ($orders as $order) {
            $newOrderItemData = OrderItem::factory()->make()->toArray();
            $orderItemService->store($order, $newOrderItemData);

            $otherNewOrderItemData = OrderItem::factory()->make()->toArray();
            $orderItemService->store($order, $otherNewOrderItemData);
        }

        $inProductionOrders = $orders->random(50);

        foreach ($inProductionOrders as $inProductionOrder) {
            $inProductionOrder->update([
                'status_id' => OrderStatus::IN_PRODUCTION,
                'launch_date' => fake()->dateTimeBetween($inProductionOrder->creation_date, $inProductionOrder->closing_date)->format('Y-m-d')
            ]);
        }

        $productionCompletedOrders = $inProductionOrders->random(35);

        foreach ($productionCompletedOrders as $productionCompletedOrder) {
            $productionCompletedOrder->update([
                'status_id' => OrderStatus::PRODUCTION_COMPLETED
            ]);
        }

        $onShipmentOrders = $productionCompletedOrders->random(20);

        foreach ($onShipmentOrders as $onShipmentOrder) {
            $onShipmentOrder->update([
                'status_id' => OrderStatus::ON_SHIPMENT
            ]);
        }

        $shippedOrders = $onShipmentOrders->random(15);
        $expiredOrders = $shippedOrders->random(5);

        foreach ($shippedOrders as $shippedOrder) {
            $shippedOrder->update([
                'status_id' => OrderStatus::SHIPPED,
                'completion_date' => fake()
                    ->dateTimeBetween($shippedOrder->launch_date, $shippedOrder->closing_date)->format('Y-m-d')
            ]);
        }

        foreach ($expiredOrders as $expiredOrder) {
            $expiredOrder->update([
                'status_id' => OrderStatus::SHIPPED,
                'completion_date' => fake()->dateTimeBetween($expiredOrder->closing_date, 'now')->format('Y-m-d')
            ]);
        }


        for ($i = 1; $i <= $numberOfItems; $i++) {
            $tollingDetail = Item::factory()->create([
                'drawing' => 'РАМГ.846322.002' . '_' . $i,
                'name' => 'Давальческая деталь' . '_' . $i,
                'item_type_id' => ItemType::TOLLING,
                'group_id' => Group::DETAIL,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => Cover::all()->random()->number,
                'route_number' => Route::all()->random()->number
            ]);

            $tollingDetails->push($tollingDetail);
        }

        $tollingDetails->random()->delete();

        for ($i = 1; $i <= $numberOfItems; $i++) {
            Item::factory()->create([
                'drawing' => 'РАД.789_012' . '_' . $i,
                'name' => 'Давальческий радиатор' . '_' . $i,
                'item_type_id' => ItemType::TOLLING,
                'group_id' => Group::VARIOUS,
                'unit_code' => Unit::U,
                'main_warehouse_code' => MainWarehouse::all()->random()->name,
                'manufacture_type_id' => ManufactureType::all()->random()->name,
                'specification_number' => null,
                'cover_number' => Cover::all()->random()->number,
                'route_number' => Route::all()->random()->number
            ]);
        }
    }
}
