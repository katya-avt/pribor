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

class StoreRequestTest extends TestCase
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
        return [
            'request_should_fail_when_item_id_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item_id' => null,
                    'per_unit_price' => 5851.35,
                    'cnt' => 77
                ]
            ],

            'request_should_fail_when_item_id_is_not_selected_from_list' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Значение не из списка.',
                    'per_unit_price' => 5851.35,
                    'cnt' => 77
                ]
            ],

            'request_should_fail_when_per_unit_price_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => null,
                    'cnt' => 77
                ]
            ],

            'request_should_fail_when_per_unit_price_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => 'Не число.',
                    'cnt' => 77
                ]
            ],

            'request_should_fail_when_per_unit_price_less_than_0' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => -5851.35,
                    'cnt' => 77
                ]
            ],

            'request_should_fail_when_per_unit_price_greater_than_99999999' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => 100000000,
                    'cnt' => 77
                ]
            ],

            'request_should_fail_when_cnt_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => 5851.35,
                    'cnt' => null
                ]
            ],

            'request_should_fail_when_cnt_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => 5851.35,
                    'cnt' => 'Не число.'
                ]
            ],

            'request_should_fail_when_cnt_less_than_0' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => 5851.35,
                    'cnt' => -77
                ]
            ],

            'request_should_fail_when_cnt_greater_than_9999' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => 5851.35,
                    'cnt' => 10000
                ]
            ],

            'request_should_fail_when_cnt_is_not_integer_for_unit_item' => [
                'passed' => false,
                'data' => [
                    'item_id' => 'Металлическая деталь',
                    'per_unit_price' => 5851.35,
                    'cnt' => 77.7
                ]
            ],
        ];
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

        $from = $this->from("/orders/{$order->id}/create");

        $response = $from->post("/orders/{$order->id}", $mockedRequestData);

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

        $data = [
            'item_id' => 'Пластмассовая деталь',
            'per_unit_price' => 5100.35,
            'cnt' => 85
        ];

        $response = $this->post("/orders/{$order->id}", $data);

        $from = $this->from("/orders/{$order->id}/create");

        $response = $from->post("/orders/{$order->id}", $data);

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

        $from = $this->from("/orders/{$order->id}/create");

        $response = $from->post("/orders/{$order->id}", $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors();
    }
}
