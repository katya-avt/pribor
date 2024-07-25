<?php

namespace App\Http\Controllers\Range\Item;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use App\Services\Range\Item\ShowService;

class ShowController extends Controller
{
    public function __invoke(Item $item, ShowService $service)
    {
        $detailAdditionalInformation = $service->getDetailAdditionalInformation($item);
        $materialsCost = $service->getMaterialsCost($item);
        $coverCost = $service->getCoverCost($item);
        $salary = $service->getSalary($item);
        $departmentsCorrelation = $service->getDepartmentsCorrelationForItem($item);

        return view('range.items.show',
            compact('detailAdditionalInformation', 'item',
                'materialsCost', 'coverCost', 'salary', 'departmentsCorrelation'));
    }
}
