<?php

namespace Tests\Feature\Requests\Range\PurchasedItems;

use App\Models\Range\Item;
use App\Models\Range\Unit;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class UpdateRequestTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ENGINEERING_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_purchase_lot_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => null,
                    'order_point' => 7
                ]
            ],

            'request_should_fail_when_purchase_lot_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 'Не число.',
                    'order_point' => 7
                ]
            ],

            'request_should_fail_when_purchase_lot_less_than_0' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => -5,
                    'order_point' => 7
                ]
            ],

            'request_should_fail_when_purchase_lot_greater_than_99999999' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 1000000000,
                    'order_point' => 7
                ]
            ],

            'request_should_fail_when_purchase_lot_is_not_integer_for_unit_purchase_lot' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 5.5,
                    'order_point' => 7
                ]
            ],

            'request_should_fail_when_order_point_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 5,
                    'order_point' => null
                ]
            ],

            'request_should_fail_when_order_point_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 5,
                    'order_point' => 'Не число.'
                ]
            ],

            'request_should_fail_when_order_point_less_than_0' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 5,
                    'order_point' => -7
                ]
            ],

            'request_should_fail_when_order_point_greater_than_99999999' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 5,
                    'order_point' => 1000000000
                ]
            ],

            'request_should_fail_when_order_point_is_not_integer_for_unit_purchase_lot' => [
                'passed' => false,
                'data' => [
                    'purchase_lot' => 5,
                    'order_point' => 7.7
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
        $purchasedItem = Item::whereHas('purchasedItem', function ($query) {
            $query->where('purchased_items.unit_code', Unit::getUnitCodeByUnitShortName(Unit::U));
        })->first();

        $from = $this->from("/purchased-items/{$purchasedItem->id}/edit");

        $response = $from->patch("/purchased-items/{$purchasedItem->id}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/purchased-items');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
