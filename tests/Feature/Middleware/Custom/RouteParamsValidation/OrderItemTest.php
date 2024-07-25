<?php

namespace Tests\Feature\Middleware\Custom\RouteParamsValidation;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OrderItemTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $user = User::where('role_id', Role::ECONOMIC_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function order_item_is_available_if_it_has_been_selected_from_list()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();
        $orderItem = $order->items()->first();

        $response = $this->get("/orders/{$order->id}/{$orderItem->id}/edit");
        $response->assertOk();
    }

    /** @test */
    public function order_item_is_not_available_if_it_has_not_been_selected_from_list()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $item = Item::onlyTrashed()->first();

        $response = $this->get("/orders/{$order->id}/{$item->id}/edit");
        $response->assertNotFound();
    }
}
