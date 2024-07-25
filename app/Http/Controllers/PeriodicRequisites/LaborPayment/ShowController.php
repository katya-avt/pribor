<?php

namespace App\Http\Controllers\PeriodicRequisites\LaborPayment;

use App\Http\Controllers\Controller;
use App\Http\Filters\FilterConstants;
use App\Http\Filters\PointBasePaymentHistoryFilter;
use App\Models\Range\Point;
use App\Http\Requests\PeriodicRequisites\LaborPayment\ShowRequest;
use function view;

class ShowController extends Controller
{
    public function __invoke(Point $point, ShowRequest $request)
    {
        $data = $request->validated();

        $filter = app()->make(PointBasePaymentHistoryFilter::class, ['queryParams' => array_filter($data)]);

        $pointBasePaymentChanges = $point->basePaymentChanges()->filter($filter)->get();

        $values = $pointBasePaymentChanges->pluck('new_value')->toArray();
        $timePoints = $pointBasePaymentChanges->pluck('change_time')->toArray();

        $periods = FilterConstants::PERIODS;

        return view('periodic-requisites.labor-payment.show',
            compact('point', 'values', 'timePoints', 'periods', 'data'));
    }
}
