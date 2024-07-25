<?php

namespace App\Http\Controllers\ItemAvailabilityAndConsumption\Availability;

use App\Http\Controllers\Controller;
use App\Http\Filters\ItemFilter;
use App\Http\Requests\ItemAvailabilityAndConsumption\Availability\IndexRequest;
use App\Models\Range\Item;
use function app;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(ItemFilter::class, ['queryParams' => array_filter($data)]);

        $items = Item::with('group', 'itemType', 'unit', 'manufactureType')->filter($filter)
            ->paginate(10);

        return view('item-availability-and-consumption.availability.index',
            compact('items', 'data'));
    }
}
