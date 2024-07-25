<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\PurchasedItemPurchasePriceHistoryFilter;
use App\Models\Range\Item;
use App\Models\Range\PurchasedItem;
use App\Models\Range\PurchasedItemPurchasePriceHistory;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PurchasedItemPurchasePriceHistoryFilterTest extends TestCase
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
    public function purchased_item_purchase_price_history_can_be_filtered_by_current_year_period()
    {
        $item = Item::has('purchasedItem')->first();

        $currentDate = Carbon::now();
        $currentYear = $currentDate->startOfYear();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentYear->toDateTimeString(),
            'new_value' => 5
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentYear->copy()->subYear()->toDateTimeString(),
            'new_value' => 5
        ]);

        $data = [
            'period' => 'year',
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChangeTimeValues = $purchasedItem->purchasePriceChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentYear->format('d.m.Y H:i:s')));

        $this->assertFalse($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentYear->copy()->subYear()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function purchased_item_purchase_price_history_can_be_filtered_by_current_quarter_period()
    {
        $item = Item::has('purchasedItem')->first();

        $currentDate = Carbon::now();
        $currentQuarter = $currentDate->startOfQuarter();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentQuarter->toDateTimeString(),
            'new_value' => 5
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentQuarter->copy()->subQuarter()->toDateTimeString(),
            'new_value' => 5
        ]);

        $data = [
            'period' => 'quarter',
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChangeTimeValues = $purchasedItem->purchasePriceChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentQuarter->format('d.m.Y H:i:s')));

        $this->assertFalse($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentQuarter->copy()->subQuarter()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function purchased_item_purchase_price_history_can_be_filtered_by_current_month_period()
    {
        $item = Item::has('purchasedItem')->first();

        $currentDate = Carbon::now();
        $currentMonth = $currentDate->startOfMonth();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentMonth->toDateTimeString(),
            'new_value' => 5
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentMonth->copy()->subMonth()->toDateTimeString(),
            'new_value' => 5
        ]);

        $data = [
            'period' => 'month',
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChangeTimeValues = $purchasedItem->purchasePriceChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentMonth->format('d.m.Y H:i:s')));

        $this->assertFalse($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentMonth->copy()->subMonth()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function purchased_item_purchase_price_history_can_be_filtered_by_selected_quarter()
    {
        $item = Item::has('purchasedItem')->first();

        $currentDate = Carbon::now();
        $currentQuarter = $currentDate->startOfQuarter();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentQuarter->toDateTimeString(),
            'new_value' => 5
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentQuarter->subQuarter()->toDateTimeString(),
            'new_value' => 5
        ]);

        $data = [
            'period' => null,
            'quarter' => $currentQuarter->format('Y-m'),
            'month' => null,
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChangeTimeValues = $purchasedItem->purchasePriceChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentQuarter->format('d.m.Y H:i:s')));

        $this->assertFalse($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentQuarter->copy()->subQuarter()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function purchased_item_purchase_price_history_can_be_filtered_by_selected_month()
    {
        $item = Item::has('purchasedItem')->first();

        $currentDate = Carbon::now();
        $currentMonth = $currentDate->startOfMonth();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentMonth->toDateTimeString(),
            'new_value' => 5
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentMonth->subMonth()->toDateTimeString(),
            'new_value' => 5
        ]);

        $data = [
            'period' => null,
            'quarter' => null,
            'month' => $currentMonth->format('Y-m'),
            'date' => null,
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChangeTimeValues = $purchasedItem->purchasePriceChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentMonth->format('d.m.Y H:i:s')));

        $this->assertFalse($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentMonth->copy()->subMonth()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function purchased_item_purchase_price_history_can_be_filtered_by_selected_date()
    {
        $item = Item::has('purchasedItem')->first();

        $currentDate = Carbon::now();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentDate->toDateTimeString(),
            'new_value' => 5
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentDate->copy()->subDay()->toDateTimeString(),
            'new_value' => 5
        ]);

        $data = [
            'period' => null,
            'quarter' => null,
            'month' => null,
            'date' => $currentDate->format('Y-m-d'),
            'from_date' => null,
            'to_date' => null
        ];

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChangeTimeValues = $purchasedItem->purchasePriceChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentDate->format('d.m.Y H:i:s')));

        $this->assertFalse($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentDate->copy()->subDay()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function purchased_item_purchase_price_history_can_be_filtered_by_selected_date_interval()
    {
        $item = Item::has('purchasedItem')->first();

        $currentDate = Carbon::now();

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentDate->toDateTimeString(),
            'new_value' => 5
        ]);

        PurchasedItemPurchasePriceHistory::create([
            'item_id' => $item->id,
            'change_time' => $currentDate->copy()->subDay()->toDateTimeString(),
            'new_value' => 5
        ]);

        $data = [
            'period' => null,
            'quarter' => null,
            'month' => null,
            'date' => null,
            'from_date' => $currentDate->format('Y-m-d'),
            'to_date' => $currentDate->copy()->addDay()->format('Y-m-d')
        ];

        $filter = app()->make(PurchasedItemPurchasePriceHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $purchasedItem = PurchasedItem::find($item->id);

        $purchasedItemPurchasePriceChangeTimeValues = $purchasedItem->purchasePriceChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentDate->format('d.m.Y H:i:s')));

        $this->assertFalse($purchasedItemPurchasePriceChangeTimeValues
            ->contains($currentDate->copy()->subDay()->format('d.m.Y H:i:s')));
    }
}
