<?php

namespace Database\Seeders\SeedersForTests;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Range\CoverItem;
use App\Models\Range\Item;
use App\Models\Range\RoutePoint;
use App\Models\Range\SpecificationItem;
use Illuminate\Database\Seeder;

class AssemblyItemSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $detailItem = Item::factory()->create([
            'drawing' => 'Металлическая деталь',
            'name' => 'Металлическая деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => '10000000',
            'cover_number' => '20000000',
            'route_number' => '30000000'
        ]);

        $detailItem->detail->update([
            'detail_size' => '10x11x12',
            'billet_size' => '12x13x14'
        ]);

        $metalItem = Item::factory()->create([
            'drawing' => 'Металл',
            'name' => 'Металл',
            'item_type_id' => 'Покупной',
            'group_id' => 'Металлы',
            'unit_code' => 'кг',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        $metalItem->purchasedItem->update([
            'purchase_price' => 80.27
        ]);

        SpecificationItem::create([
            'specification_number' => $detailItem->specification_number,
            'item_id' => $metalItem->id,
            'cnt' => 0.28462
        ]);

        $galvanicItem = Item::factory()->create([
            'drawing' => 'Гальваническое покрытие',
            'name' => 'Гальваническое покрытие',
            'item_type_id' => 'Собственный',
            'group_id' => 'Гальванические покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => '40000000',
            'cover_number' => null,
            'route_number' => '50000000'
        ]);

        CoverItem::factory()->create([
            'cover_number' => $detailItem->cover_number,
            'item_id' => $galvanicItem->id,
            'area' => 165.37,
            'consumption' => 0.00008
        ]);

        RoutePoint::create([
            'route_number' => $detailItem->route_number,
            'point_number' => 1,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 17.39,
            'working_time' => 17.02,
            'lead_time' => 3.28
        ]);

        RoutePoint::create([
            'route_number' => $detailItem->route_number,
            'point_number' => 2,
            'point_code' => '1.1',
            'operation_code' => '8056',
            'rate_code' => 'С2',
            'unit_time' => 18.90,
            'working_time' => 2.95,
            'lead_time' => 5.05
        ]);

        RoutePoint::create([
            'route_number' => $detailItem->route_number,
            'point_number' => 3,
            'point_code' => '2',
            'operation_code' => '0860',
            'rate_code' => 'С3',
            'unit_time' => 8.22,
            'working_time' => 8.52,
            'lead_time' => 5.08
        ]);

        RoutePoint::create([
            'route_number' => $detailItem->route_number,
            'point_number' => 4,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 17.39,
            'working_time' => 17.02,
            'lead_time' => 3.28
        ]);

        $chemicalItem = Item::factory()->create([
            'drawing' => 'Химикат',
            'name' => 'Химикат',
            'item_type_id' => 'Покупной',
            'group_id' => 'Химикаты',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        $chemicalItem->purchasedItem->update([
            'purchase_price' => 81.27
        ]);

        SpecificationItem::create([
            'specification_number' => $galvanicItem->specification_number,
            'item_id' => $chemicalItem->id,
            'cnt' => 0.20277
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItem->route_number,
            'point_number' => 1,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 18.38,
            'working_time' => 18.03,
            'lead_time' => 4.28
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItem->route_number,
            'point_number' => 2,
            'point_code' => '1.1',
            'operation_code' => '8056',
            'rate_code' => 'С2',
            'unit_time' => 18.80,
            'working_time' => 3.95,
            'lead_time' => 5.08
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItem->route_number,
            'point_number' => 3,
            'point_code' => '3г',
            'operation_code' => '0860',
            'rate_code' => 'С3',
            'unit_time' => 8.25,
            'working_time' => 8.55,
            'lead_time' => 5.05
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItem->route_number,
            'point_number' => 4,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 18.38,
            'working_time' => 18.03,
            'lead_time' => 4.28
        ]);

        $plasticDetailItem = Item::factory()->create([
            'drawing' => 'Пластмассовая деталь',
            'name' => 'Пластмассовая деталь',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => '60000000',
            'cover_number' => '70000000',
            'route_number' => '80000000'
        ]);

        $plasticItem = Item::factory()->create([
            'drawing' => 'Пластмасса',
            'name' => 'Пластмасса',
            'item_type_id' => 'Покупной',
            'group_id' => 'Пластмассы',
            'unit_code' => 'кг',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        SpecificationItem::create([
            'specification_number' => $plasticDetailItem->specification_number,
            'item_id' => $plasticItem->id,
            'cnt' => 0.63410
        ]);

        $paintItem = Item::factory()->create([
            'drawing' => 'Лакокрасочное покрытие',
            'name' => 'Лакокрасочное покрытие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Лакокрасочные покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        CoverItem::factory()->create([
            'cover_number' => $plasticDetailItem->cover_number,
            'item_id' => $paintItem->id,
            'area' => 161.75,
            'consumption' => 0.00005
        ]);

        RoutePoint::create([
            'route_number' => $plasticDetailItem->route_number,
            'point_number' => 1,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 20.38,
            'working_time' => 20.03,
            'lead_time' => 5.28
        ]);

        RoutePoint::create([
            'route_number' => $plasticDetailItem->route_number,
            'point_number' => 2,
            'point_code' => '1.1',
            'operation_code' => '8056',
            'rate_code' => 'С2',
            'unit_time' => 15.80,
            'working_time' => 2.95,
            'lead_time' => 7.08
        ]);

        RoutePoint::create([
            'route_number' => $plasticDetailItem->route_number,
            'point_number' => 3,
            'point_code' => '2',
            'operation_code' => '0860',
            'rate_code' => 'С3',
            'unit_time' => 10.25,
            'working_time' => 18.55,
            'lead_time' => 3.05
        ]);

        RoutePoint::create([
            'route_number' => $plasticDetailItem->route_number,
            'point_number' => 4,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 20.38,
            'working_time' => 20.03,
            'lead_time' => 5.28
        ]);

        $assemblyItem = Item::factory()->create([
            'drawing' => 'Сборочная единица',
            'name' => 'Сборочная единица',
            'item_type_id' => 'Собственный',
            'group_id' => 'Сборочные единицы',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => '90000000',
            'cover_number' => '100000000',
            'route_number' => '110000000'
        ]);

        $fastenerItem = Item::factory()->create([
            'drawing' => 'Крепеж',
            'name' => 'Крепеж',
            'item_type_id' => 'Покупной',
            'group_id' => 'Крепеж',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        SpecificationItem::create([
            'specification_number' => $assemblyItem->specification_number,
            'item_id' => $fastenerItem->id,
            'cnt' => 5
        ]);

        SpecificationItem::create([
            'specification_number' => $assemblyItem->specification_number,
            'item_id' => $detailItem->id,
            'cnt' => 1
        ]);

        SpecificationItem::create([
            'specification_number' => $assemblyItem->specification_number,
            'item_id' => $plasticDetailItem->id,
            'cnt' => 3
        ]);

        CoverItem::factory()->create([
            'cover_number' => $assemblyItem->cover_number,
            'item_id' => $paintItem->id,
            'area' => 181.75,
            'consumption' => 0.00007
        ]);

        RoutePoint::create([
            'route_number' => $assemblyItem->route_number,
            'point_number' => 1,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 25.38,
            'working_time' => 25.03,
            'lead_time' => 7.28
        ]);

        RoutePoint::create([
            'route_number' => $assemblyItem->route_number,
            'point_number' => 2,
            'point_code' => '1.1',
            'operation_code' => '8056',
            'rate_code' => 'С2',
            'unit_time' => 17.80,
            'working_time' => 5.95,
            'lead_time' => 8.08
        ]);

        RoutePoint::create([
            'route_number' => $assemblyItem->route_number,
            'point_number' => 3,
            'point_code' => '2',
            'operation_code' => '0860',
            'rate_code' => 'С3',
            'unit_time' => 12.25,
            'working_time' => 20.55,
            'lead_time' => 5.05
        ]);

        RoutePoint::create([
            'route_number' => $assemblyItem->route_number,
            'point_number' => 4,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 25.38,
            'working_time' => 25.03,
            'lead_time' => 7.28
        ]);

        $otherAssemblyItem = Item::factory()->create([
            'drawing' => 'Устройство',
            'name' => 'Устройство',
            'item_type_id' => 'Собственный',
            'group_id' => 'Сборочные единицы',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => '120000000',
            'cover_number' => '130000000',
            'route_number' => '140000000'
        ]);

        $detailItemForOtherAssemblyItem = Item::factory()->create([
            'drawing' => 'Металлическая деталь для сборочной единицы',
            'name' => 'Металлическая деталь для сборочной единицы',
            'item_type_id' => 'Собственный',
            'group_id' => 'Детали',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => '150000000',
            'cover_number' => '160000000',
            'route_number' => '170000000'
        ]);

        $metalItemForOtherAssemblyItem = Item::factory()->create([
            'drawing' => 'Металл для детали сборочной единицы',
            'name' => 'Металл для детали сборочной единицы',
            'item_type_id' => 'Покупной',
            'group_id' => 'Металлы',
            'unit_code' => 'кг',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        SpecificationItem::create([
            'specification_number' => $detailItemForOtherAssemblyItem->specification_number,
            'item_id' => $metalItemForOtherAssemblyItem->id,
            'cnt' => 0.58462
        ]);

        $galvanicItemForOtherAssemblyItem = Item::factory()->create([
            'drawing' => 'Гальваническое покрытие для детали сборочной единицы',
            'name' => 'Гальваническое покрытие для детали сборочной единицы',
            'item_type_id' => 'Собственный',
            'group_id' => 'Гальванические покрытия',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => '180000000',
            'cover_number' => null,
            'route_number' => '190000000'
        ]);

        CoverItem::factory()->create([
            'cover_number' => $detailItemForOtherAssemblyItem->cover_number,
            'item_id' => $galvanicItemForOtherAssemblyItem->id,
            'area' => 265.37,
            'consumption' => 0.00008
        ]);

        RoutePoint::create([
            'route_number' => $detailItemForOtherAssemblyItem->route_number,
            'point_number' => 1,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 20.39,
            'working_time' => 20.02,
            'lead_time' => 23.28
        ]);

        RoutePoint::create([
            'route_number' => $detailItemForOtherAssemblyItem->route_number,
            'point_number' => 2,
            'point_code' => '1.1',
            'operation_code' => '8056',
            'rate_code' => 'С2',
            'unit_time' => 20.90,
            'working_time' => 12.95,
            'lead_time' => 15.05
        ]);

        RoutePoint::create([
            'route_number' => $detailItemForOtherAssemblyItem->route_number,
            'point_number' => 3,
            'point_code' => '2',
            'operation_code' => '0860',
            'rate_code' => 'С3',
            'unit_time' => 18.22,
            'working_time' => 18.52,
            'lead_time' => 15.08
        ]);

        RoutePoint::create([
            'route_number' => $detailItemForOtherAssemblyItem->route_number,
            'point_number' => 4,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 20.39,
            'working_time' => 20.02,
            'lead_time' => 23.28
        ]);

        $chemicalItemForOtherAssemblyItem = Item::factory()->create([
            'drawing' => 'Химикат для детали сборочной единицы',
            'name' => 'Химикат для детали сборочной единицы',
            'item_type_id' => 'Покупной',
            'group_id' => 'Химикаты',
            'unit_code' => 'л',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        SpecificationItem::create([
            'specification_number' => $galvanicItemForOtherAssemblyItem->specification_number,
            'item_id' => $chemicalItemForOtherAssemblyItem->id,
            'cnt' => 0.30377
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItemForOtherAssemblyItem->route_number,
            'point_number' => 1,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 17.37,
            'working_time' => 17.03,
            'lead_time' => 5.28
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItemForOtherAssemblyItem->route_number,
            'point_number' => 2,
            'point_code' => '1.1',
            'operation_code' => '8056',
            'rate_code' => 'С2',
            'unit_time' => 17.70,
            'working_time' => 4.95,
            'lead_time' => 4.08
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItemForOtherAssemblyItem->route_number,
            'point_number' => 3,
            'point_code' => '3г',
            'operation_code' => '0860',
            'rate_code' => 'С3',
            'unit_time' => 7.25,
            'working_time' => 7.55,
            'lead_time' => 4.05
        ]);

        RoutePoint::create([
            'route_number' => $galvanicItemForOtherAssemblyItem->route_number,
            'point_number' => 4,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 17.37,
            'working_time' => 17.03,
            'lead_time' => 5.28
        ]);

        $variousItem = Item::factory()->create([
            'drawing' => 'Разный материал',
            'name' => 'Разный материал',
            'item_type_id' => 'Покупной',
            'group_id' => 'Разные материалы',
            'unit_code' => 'шт',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        $cableItem = Item::factory()->create([
            'drawing' => 'Кабельное изделие',
            'name' => 'Кабельное изделие',
            'item_type_id' => 'Покупной',
            'group_id' => 'Кабельные изделия',
            'unit_code' => 'м',
            'main_warehouse_code' => 'Склад материалов 1 цеха',
            'manufacture_type_id' => 'Страховой запас',
            'specification_number' => null,
            'cover_number' => null,
            'route_number' => null
        ]);

        SpecificationItem::create([
            'specification_number' => $otherAssemblyItem->specification_number,
            'item_id' => $detailItemForOtherAssemblyItem->id,
            'cnt' => 4
        ]);

        SpecificationItem::create([
            'specification_number' => $otherAssemblyItem->specification_number,
            'item_id' => $assemblyItem->id,
            'cnt' => 2
        ]);

        SpecificationItem::create([
            'specification_number' => $otherAssemblyItem->specification_number,
            'item_id' => $variousItem->id,
            'cnt' => 7
        ]);

        SpecificationItem::create([
            'specification_number' => $otherAssemblyItem->specification_number,
            'item_id' => $cableItem->id,
            'cnt' => 0.82008
        ]);

        SpecificationItem::create([
            'specification_number' => $otherAssemblyItem->specification_number,
            'item_id' => $fastenerItem->id,
            'cnt' => 8
        ]);

        CoverItem::factory()->create([
            'cover_number' => $otherAssemblyItem->cover_number,
            'item_id' => $paintItem->id,
            'area' => 258.75,
            'consumption' => 0.00008
        ]);

        RoutePoint::create([
            'route_number' => $otherAssemblyItem->route_number,
            'point_number' => 1,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 28.38,
            'working_time' => 28.03,
            'lead_time' => 8.28
        ]);

        RoutePoint::create([
            'route_number' => $otherAssemblyItem->route_number,
            'point_number' => 2,
            'point_code' => '1.1',
            'operation_code' => '8056',
            'rate_code' => 'С2',
            'unit_time' => 18.80,
            'working_time' => 7.95,
            'lead_time' => 10.08
        ]);

        RoutePoint::create([
            'route_number' => $otherAssemblyItem->route_number,
            'point_number' => 3,
            'point_code' => '2',
            'operation_code' => '0860',
            'rate_code' => 'С3',
            'unit_time' => 15.25,
            'working_time' => 25.55,
            'lead_time' => 8.05
        ]);

        RoutePoint::create([
            'route_number' => $otherAssemblyItem->route_number,
            'point_number' => 4,
            'point_code' => 'Монт',
            'operation_code' => '0334',
            'rate_code' => 'С1',
            'unit_time' => 28.38,
            'working_time' => 28.03,
            'lead_time' => 8.28
        ]);
    }
}
