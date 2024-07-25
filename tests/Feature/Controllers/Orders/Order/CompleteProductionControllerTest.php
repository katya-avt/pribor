<?php

namespace Tests\Feature\Controllers\Orders\Order;

use App\Models\Orders\Order;
use App\Models\Orders\OrderItemSpecification;
use App\Models\Orders\OrderStatus;
use App\Models\Range\Item;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompleteProductionControllerTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp();

        $this->withoutExceptionHandling();

        $user = User::where('role_id', Role::PROCUREMENT_AND_SALES_DEPARTMENT_OFFICER)->first();
        Sanctum::actingAs($user);
    }

    /** @test */
    public function a_production_of_in_production_order_cannot_be_completed_when_order_has_missing_items()
    {
        $inProductionOrder = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        $maxRequiredItemCnt = $inProductionOrder->items()->max('order_item.cnt');

        Item::whereIn('id', $inProductionOrder->items->pluck('id')->toArray())
            ->update(['cnt' => $maxRequiredItemCnt - 1]);
        $inProductionOrder->refresh();

        $response = $this->patch("/orders/{$inProductionOrder->id}/complete-production");
        $inProductionOrder->refresh();

        $response->assertRedirect("/orders/status/" . OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME);

        $response = $this->get("/orders/status/" . OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME);
        $response->assertSee(__('messages.order_production_cannot_be_completed'));

        $this->assertDatabaseHas('orders', ['id' => $inProductionOrder->id, 'status_id' => OrderStatus::IN_PRODUCTION]);
    }

    /** @test */
    public function a_production_of_in_production_order_cannot_be_completed_when_order_components_has_missing_items()
    {
        $inProductionOrder = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        $requiredComponentsCnt = OrderItemSpecification::selectRaw('component_id, SUM(component_cnt) as cnt')
            ->where('order_id', $inProductionOrder->id)->groupBy('component_id')->get();

        $maxRequiredComponentCnt = $requiredComponentsCnt->pluck('cnt')->max();

        Item::whereIn('id', $requiredComponentsCnt->pluck('component_id')->toArray())
            ->update(['cnt' => $maxRequiredComponentCnt - 1]);
        $inProductionOrder->refresh();

        $response = $this->patch("/orders/{$inProductionOrder->id}/complete-production");
        $inProductionOrder->refresh();

        $response->assertRedirect("/orders/status/" . OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME);

        $response = $this->get("/orders/status/" . OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME);
        $response->assertSee(__('messages.order_production_cannot_be_completed'));

        $this->assertDatabaseHas('orders', ['id' => $inProductionOrder->id, 'status_id' => OrderStatus::IN_PRODUCTION]);
    }

    /** @test */
    public function a_production_of_in_production_order_can_be_completed()
    {
        $inProductionOrder = Order::where('status_id', OrderStatus::IN_PRODUCTION)->first();

        Item::query()->update(['cnt' => 0]);
        $inProductionOrder->refresh();

        $maxRequiredItemCnt = $inProductionOrder->items()->max('order_item.cnt');
        Item::query()->whereIn('id', $inProductionOrder->items->pluck('id')->toArray())
            ->update(['cnt' => $maxRequiredItemCnt]);
        $inProductionOrder->refresh();

        $requiredComponentsCnt = OrderItemSpecification::selectRaw('component_id, SUM(component_cnt) as cnt')
            ->where('order_id', $inProductionOrder->id)->groupBy('component_id')->get();
        $maxRequiredComponentCnt = $requiredComponentsCnt->pluck('cnt')->max();
        Item::query()->whereIn('id', $requiredComponentsCnt->pluck('component_id')->toArray())
            ->update(['cnt' => $maxRequiredComponentCnt]);

        $response = $this->patch("/orders/{$inProductionOrder->id}/complete-production");
        $inProductionOrder->refresh();

        foreach ($inProductionOrder->items as $orderItem) {
            $orderItemCnt = $orderItem->pivot->cnt;
            $newItemCurrentCnt = round($maxRequiredItemCnt - $orderItemCnt, 2);

            $this->assertEquals($newItemCurrentCnt, $orderItem->cnt);
        }

        foreach ($requiredComponentsCnt as $orderComponent) {
            $orderComponentCurrentCnt = Item::find($orderComponent->component_id)->cnt;
            $newComponentCurrentCnt = round($maxRequiredComponentCnt - $orderComponent->cnt, 2);

            $this->assertEquals($newComponentCurrentCnt, $orderComponentCurrentCnt);
        }

        $response->assertRedirect("/orders/status/" . OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME);

        $response = $this->get("/orders/status/" . OrderStatus::PRODUCTION_COMPLETED_URL_PARAM_NAME);
        $response->assertSee(__('messages.successful_complete_production'));

        $this->assertDatabaseHas('orders', ['id' => $inProductionOrder->id, 'status_id' => OrderStatus::PRODUCTION_COMPLETED]);
    }
}
