<?php

namespace App\Http\Controllers\ChoiceModals\Range\Item;

use App\Http\Controllers\Controller;
use App\Http\Filters\ItemFilter;
use App\Http\Requests\ChoiceModals\Range\Item\IndexRequest;
use App\Models\Range\Group;
use App\Models\Range\Item;
use App\Models\Range\ItemType;
use App\Models\Range\MainWarehouse;
use App\Models\Range\ManufactureType;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(ItemFilter::class, ['queryParams' => array_filter($data)]);

        $items = Item::with('group', 'itemType', 'unit', 'manufactureType')->filter($filter)
            ->paginate(10);

        $groups = Group::whereDoesntHave('groups')->get();
        $itemTypes = ItemType::all();
        $mainWarehouses = MainWarehouse::all();
        $manufactureTypes = ManufactureType::all();

        return view('choice-modals.range.item.index',
            compact('items', 'groups', 'itemTypes', 'mainWarehouses', 'manufactureTypes',
                'data'));
    }
}
