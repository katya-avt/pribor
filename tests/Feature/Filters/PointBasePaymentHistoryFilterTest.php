<?php

namespace Tests\Feature\Filters;

use App\Http\Filters\PointBasePaymentHistoryFilter;
use App\Models\Range\Point;
use App\Models\Range\PointBasePaymentHistory;
use App\Models\Users\Role;
use App\Models\Users\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Carbon;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class PointBasePaymentHistoryFilterTest extends TestCase
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
    public function point_base_payment_history_can_be_filtered_by_current_year_period()
    {
        $point = Point::first();

        $currentDate = Carbon::now();
        $currentYear = $currentDate->startOfYear();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentYear->toDateTimeString(),
            'new_value' => 5
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
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

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $basePaymentChangeTimeValues = $point->basePaymentChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($basePaymentChangeTimeValues
            ->contains($currentYear->format('d.m.Y H:i:s')));

        $this->assertFalse($basePaymentChangeTimeValues
            ->contains($currentYear->copy()->subYear()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function point_base_payment_history_can_be_filtered_by_current_quarter_period()
    {
        $point = Point::first();

        $currentDate = Carbon::now();
        $currentQuarter = $currentDate->startOfQuarter();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentQuarter->toDateTimeString(),
            'new_value' => 5
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
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

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $basePaymentChangeTimeValues = $point->basePaymentChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($basePaymentChangeTimeValues
            ->contains($currentQuarter->format('d.m.Y H:i:s')));

        $this->assertFalse($basePaymentChangeTimeValues
            ->contains($currentQuarter->copy()->subQuarter()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function point_base_payment_history_can_be_filtered_by_current_month_period()
    {
        $point = Point::first();

        $currentDate = Carbon::now();
        $currentMonth = $currentDate->startOfMonth();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentMonth->toDateTimeString(),
            'new_value' => 5
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
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

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $basePaymentChangeTimeValues = $point->basePaymentChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($basePaymentChangeTimeValues
            ->contains($currentMonth->format('d.m.Y H:i:s')));

        $this->assertFalse($basePaymentChangeTimeValues
            ->contains($currentMonth->copy()->subMonth()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function point_base_payment_history_can_be_filtered_by_selected_quarter()
    {
        $point = Point::first();

        $currentDate = Carbon::now();
        $currentQuarter = $currentDate->startOfQuarter();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentDate->toDateTimeString(),
            'new_value' => 5
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentDate->copy()->subQuarter()->toDateTimeString(),
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

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $basePaymentChangeTimeValues = $point->basePaymentChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($basePaymentChangeTimeValues
            ->contains($currentQuarter->format('d.m.Y H:i:s')));

        $this->assertFalse($basePaymentChangeTimeValues
            ->contains($currentQuarter->copy()->subQuarter()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function point_base_payment_history_can_be_filtered_by_selected_month()
    {
        $point = Point::first();

        $currentDate = Carbon::now();
        $currentMonth = $currentDate->startOfMonth();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentMonth->toDateTimeString(),
            'new_value' => 5
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentMonth->copy()->subMonth()->toDateTimeString(),
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

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $basePaymentChangeTimeValues = $point->basePaymentChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($basePaymentChangeTimeValues
            ->contains($currentMonth->format('d.m.Y H:i:s')));

        $this->assertFalse($basePaymentChangeTimeValues
            ->contains($currentMonth->copy()->subMonth()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function point_base_payment_history_can_be_filtered_by_selected_date()
    {
        $point = Point::first();

        $currentDate = Carbon::now();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentDate->toDateTimeString(),
            'new_value' => 5
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
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

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $basePaymentChangeTimeValues = $point->basePaymentChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($basePaymentChangeTimeValues
            ->contains($currentDate->format('d.m.Y H:i:s')));

        $this->assertFalse($basePaymentChangeTimeValues
            ->contains($currentDate->copy()->subDay()->format('d.m.Y H:i:s')));
    }

    /** @test */
    public function point_base_payment_history_can_be_filtered_by_selected_date_interval()
    {
        $point = Point::first();

        $currentDate = Carbon::now();

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
            'change_time' => $currentDate->toDateTimeString(),
            'new_value' => 5
        ]);

        PointBasePaymentHistory::create([
            'point_code' => $point->code,
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

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $basePaymentChangeTimeValues = $point->basePaymentChanges()
            ->filter($filter)->get()->pluck('change_time');

        $this->assertTrue($basePaymentChangeTimeValues
            ->contains($currentDate->format('d.m.Y H:i:s')));

        $this->assertFalse($basePaymentChangeTimeValues
            ->contains($currentDate->copy()->subDay()->format('d.m.Y H:i:s')));
    }
}
