<?php

namespace Tests\Feature\Requests\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Database\Seeders\SeedersForTests\AssemblyItemSeeder;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return (new StoreRequestTest())->validationProvider();
    }

    /**
     * @test
     * @dataProvider validationProvider
     * @param bool $shouldPass
     * @param array $mockedRequestData
     */
    public function validation_results_as_expected($shouldPass, $mockedRequestData)
    {
        $this->seed(AssemblyItemSeeder::class);

        $order = Order::create([
            'code' => 'Код',
            'name' => 'Наименование',
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null,
        ]);

        $orderItem = Item::getItemByDrawing('Пластмассовая деталь');

        $data = [
            'item_id' => $orderItem->drawing,
            'per_unit_price' => 5100.35,
            'cnt' => 85
        ];

        $response = $this->post("/orders/{$order->id}", $data);

        $from = $this->from("/orders/{$order->id}/{$orderItem->id}/edit");

        $response = $from->patch("/orders/{$order->id}/{$orderItem->id}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect("/orders/{$order->id}");
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }

    /** @test */
    public function request_should_fail_when_selected_item_id_is_already_on_list()
    {
        $this->seed(AssemblyItemSeeder::class);

        $order = Order::create([
            'code' => 'Код',
            'name' => 'Наименование',
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null,
        ]);

        $firstOrderItem = Item::getItemByDrawing('Пластмассовая деталь');

        $data = [
            'item_id' => $firstOrderItem->drawing,
            'per_unit_price' => 5100.35,
            'cnt' => 85
        ];

        $response = $this->post("/orders/{$order->id}", $data);

        $secondOrderItem = Item::getItemByDrawing('Металлическая деталь');

        $data = [
            'item_id' => $secondOrderItem->drawing,
            'per_unit_price' => 5100.35,
            'cnt' => 85
        ];

        $response = $this->post("/orders/{$order->id}", $data);

        $from = $this->from("/orders/{$order->id}/{$firstOrderItem->id}/edit");

        $response = $from->patch("/orders/{$order->id}/{$firstOrderItem->id}", $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }

    /** @test */
    public function request_should_fail_when_order_item_does_not_have_full_manufacturing_process()
    {
        $this->seed(AssemblyItemSeeder::class);

        $order = Order::create([
            'code' => 'Код',
            'name' => 'Наименование',
            'closing_date' => '2024-12-12',
            'customer_inn' => 'АВТ_П3_17',
            'note' => null,
        ]);

        $orderItem = Item::getItemByDrawing('Пластмассовая деталь');

        $data = [
            'item_id' => $orderItem->drawing,
            'per_unit_price' => 5100.35,
            'cnt' => 85
        ];

        $response = $this->post("/orders/{$order->id}", $data);

        $detailItem = Item::getItemByDrawing('Металлическая деталь');

        $detailItem->update([
            'specification_number' => null
        ]);
        $detailItem->refresh();

        $data = [
            'item_id' => 'Устройство',
            'per_unit_price' => 51000.35,
            'cnt' => 85
        ];

        $from = $this->from("/orders/{$order->id}/{$orderItem->id}/edit");

        $response = $from->patch("/orders/{$order->id}/{$orderItem->id}", $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
