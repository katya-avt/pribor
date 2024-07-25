<?php

namespace Tests\Feature\Controllers\Orders\Order;

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
    public function a_pending_order_can_be_deleted()
    {
        $order = Order::where('status_id', OrderStatus::PENDING)->first();

        $response = $this->delete("/orders/{$order->id}");

        $deletedRecords = DB::table('order_item')
            ->where('order_id', $order->id)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedRecords = DB::table('order_item_specification')
            ->where('order_id', $order->id)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedRecords = DB::table('order_item_route')
            ->where('order_id', $order->id)
            ->get()->isEmpty();

        $this->assertTrue($deletedRecords);

        $deletedOrder = Order::find($order->id);
        $this->assertNull($deletedOrder);

        $response->assertRedirect("orders/status/" . OrderStatus::PENDING_URL_PARAM_NAME);

        $response = $this->get("orders/status/" . OrderStatus::PENDING_URL_PARAM_NAME);
        $response->assertSee(__('messages.successful_delete'));
    }
}
