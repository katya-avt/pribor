<?php

namespace Tests\Feature\Controllers\Range\Item;

use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Database\Seeders\SeedersForTests\AssemblyItemSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ShowControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function response_for_route_items_show_is_view_range_items_show_with_single_item()
    {
        $item = Item::first();

        $response = $this->get("/items/{$item->id}");
        $response->assertOk();

        $response->assertViewIs('range.items.show');
    }

    /** @test */
    public function item_card_for_detail_is_correct()
    {
        $this->seed(AssemblyItemSeeder::class);

        $detailItem = Item::getItemByDrawing('Металлическая деталь');

        $response = $this->get("/items/{$detailItem->id}");
        $response->assertOk();

        $response->assertSee($detailItem->drawing);
        $response->assertSee($detailItem->name);
        $response->assertSee($detailItem->unit->short_name);
        $response->assertSee($detailItem->itemType->name);
        $response->assertSee($detailItem->group->name);
        $response->assertSee($detailItem->mainWarehouse->name);
        $response->assertSee($detailItem->manufactureType->name);

        $response->assertSee($detailItem->detail->detail_size);
        $response->assertSee($detailItem->detail->billet_size);

        $blackWeight = 0.28462;
        $response->assertSee($blackWeight);

        $netWeight = round((10*11*12) * (0.28462/(12*13*14)), 5);
        $response->assertSee($netWeight);

        $kim = round($netWeight/$blackWeight, 3);
        $response->assertSee($kim);

        $response->assertSee($detailItem->specification_number);
        $response->assertSee($detailItem->cover_number);
        $response->assertSee($detailItem->route_number);

        $materialsCost = 0.28462 * 80.27;

        $coverCntForDetail = 165.37 * 0.00008;
        $coverSpecificationCost = 0.20277 * 81.27;
        $coverRouteCost = (18.38 + 18.03 + 4.28) * 5.21 * 1 + (18.80 + 3.95 + 5.08) * 5.21 * 1.5 + (8.25 + 8.55 + 5.05) * 6.77 * 2 + (18.38 + 18.03 + 4.28) * 5.21 * 1;

        $coverCost = $coverCntForDetail * ($coverSpecificationCost + $coverRouteCost);

        $routeCost = (17.39 + 17.02 + 3.28) * 5.21 * 1 + (18.90 + 2.95 + 5.05) * 5.21 * 1.5 + (8.22 + 8.52 + 5.08) * 6.25 * 2 + (17.39 + 17.02 + 3.28) * 5.21 * 1;

        $totalDetailCost = $materialsCost + $coverCost + $routeCost;

        $response->assertSeeInOrder([
            round($totalDetailCost, 2),
            round($materialsCost, 2),
            round($coverCost, 2),
            round($routeCost, 2)
        ]);

        //В технологическом маршруте $detailItem всего 4 точки:
        //Монт - 2, 1.1 - 1, 2 - 1
        //Т.е. Заготовительный цех - 2, Механический цех - 1, Сборочный цех - 1, Гальванический цех - 0, ОТК - 0
        //Тогда:
        //Гальванический цех: 3 нуля
        //Заготовительный цех: 2/4 от себестоимости материалов, покрытия и сдельной зарплаты
        //Механический цех: 1/4 от себестоимости материалов, покрытия и сдельной зарплаты
        //ОТК: 3 нуля
        //Сборочный цех: 1/4 от себестоимости материалов, покрытия и сдельной зарплаты

        $response->assertSeeInOrder([
            0, 0, 0,
            round((2/4) * $materialsCost, 2), round((2/4) * $coverCost, 2), round((2/4) * $routeCost, 2),
            round((1/4) * $materialsCost, 2), round((1/4) * $coverCost, 2), round((1/4) * $routeCost, 2),
            0, 0, 0,
            round((1/4) * $materialsCost, 2), round((1/4) * $coverCost, 2), round((1/4) * $routeCost, 2)
        ]);
    }

    /** @test */
    public function item_card_for_purchased_item_is_correct()
    {
        $this->seed(AssemblyItemSeeder::class);

        $fastenerItem = Item::getItemByDrawing('Крепеж');

        $response = $this->get("/items/{$fastenerItem->id}");
        $response->assertOk();

        $response->assertSee($fastenerItem->drawing);
        $response->assertSee($fastenerItem->name);
        $response->assertSee($fastenerItem->unit->short_name);
        $response->assertSee($fastenerItem->itemType->name);
        $response->assertSee($fastenerItem->group->name);
        $response->assertSee($fastenerItem->mainWarehouse->name);
        $response->assertSee($fastenerItem->manufactureType->name);

        $response->assertSee($fastenerItem->purchasedItem->purchase_price);
        $response->assertSee($fastenerItem->purchasedItem->purchase_lot);
        $response->assertSee(round($fastenerItem->purchasedItem->purchase_price * $fastenerItem->purchasedItem->purchase_lot, 2));
        $response->assertSee($fastenerItem->purchasedItem->order_point);
        $response->assertSee($fastenerItem->purchasedItem->unit_factor);
        $response->assertSee($fastenerItem->purchasedItem->unit->short_name);

        //Себестоимость материалов покупного изделия - это цена его покупки
        //Покрытия и маршрута у данного покупного изделия нет
        $materialsCost = round($fastenerItem->purchasedItem->purchase_price, 2);
        $coverCost = 0;
        $routeCost = 0;
        $totalFastenerCost = $materialsCost + $coverCost + $routeCost;

        $response->assertSeeInOrder([
            $totalFastenerCost, $materialsCost, $coverCost, $routeCost
        ]);

        //Технологического маршрута у данного покупного изделия нет, поэтому распределения себестоимости по цехам тоже нет
        $response->assertSeeInOrder([
            0, 0, 0,
            0, 0, 0,
            0, 0, 0,
            0, 0, 0
        ]);
    }
}
