<?php

namespace Tests\Feature\Requests\PeriodicRequisites\PurchasePrice;

use App\Models\Range\Item;
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

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    public function validationProvider()
    {
        return [
            'request_should_fail_when_purchase_price_is_not_provided' => [
                'passed' => false,
                'data' => [
                    'purchase_price' => null
                ]
            ],

            'request_should_fail_when_purchase_price_is_not_a_number' => [
                'passed' => false,
                'data' => [
                    'purchase_price' => 'Не число.'
                ]
            ],

            'request_should_fail_when_purchase_price_less_than_0' => [
                'passed' => false,
                'data' => [
                    'purchase_price' => -5
                ]
            ],

            'request_should_fail_when_purchase_price_greater_than_99999999' => [
                'passed' => false,
                'data' => [
                    'purchase_price' => 1000000000
                ]
            ]
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
        $purchasedItem = Item::has('purchasedItem')->first();

        $from = $this->from("/purchase-price/{$purchasedItem->id}/edit");
        $response = $from->patch("/purchase-price/{$purchasedItem->id}", $mockedRequestData);

        if ($shouldPass) {
            $response->assertRedirect('/purchase-price');
        } else {
            $response->assertRedirect();
            $response->assertSessionHasErrors();
        }
    }
}
