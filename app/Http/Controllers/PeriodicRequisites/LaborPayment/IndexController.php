<?php

namespace App\Http\Controllers\PeriodicRequisites\LaborPayment;

use App\Http\Controllers\Controller;
use App\Http\Filters\PointFilter;
use App\Models\Range\Point;
use App\Http\Requests\PeriodicRequisites\LaborPayment\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(PointFilter::class, ['queryParams' => array_filter($data)]);

        $points = Point::filter($filter)->paginate(10);

        return view('periodic-requisites.labor-payment.index',
            compact('points', 'data'));
    }
}
