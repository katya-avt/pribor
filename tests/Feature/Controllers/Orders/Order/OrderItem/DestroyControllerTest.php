<?php

namespace Tests\Feature\Controllers\Orders\Order\OrderItem;

use App\Models\Orders\Order;
use App\Models\Orders\OrderStatus;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DestroyControllerTest extends TestCase
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
    public function an_order_item_can_be_deleted()
    {
        $order = Order::has('items', '=', 2)->where('status_id', OrderStatus::PENDING)->first();

        $orderItem = $order->items->first();

        $response = $this->delete("/orders/{$order->id}/{$orderItem->id}");
        $order->refresh();

        $deletedRecord = DB::table('order_item')
            ->where('order_id', $order->id)
            ->where('item_id', $orderItem->id)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecord);

        $deletedRecord = DB::table('order_item_specification')
            ->where('order_id', $order->id)
            ->where('item_id', $orderItem->id)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecord);

        $deletedRecord = DB::table('order_item_route')
            ->where('order_id', $order->id)
            ->where('item_id', $orderItem->id)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecord);

        $remainedOrderItem = $order->items->last();
        $this->assertEquals($remainedOrderItem->pivot->amount, $order->amount);
        $this->assertEquals($remainedOrderItem->pivot->cost, $order->cost);

        $response->assertRedirect("orders/{$order->id}");

        $response = $this->get("orders/{$order->id}");
        $response->assertSee(__('messages.successful_delete'));
    }
}
