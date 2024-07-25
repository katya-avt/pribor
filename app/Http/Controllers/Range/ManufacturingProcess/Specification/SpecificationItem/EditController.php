<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use function view;

class EditController extends Controller
{
    public function __invoke(Specification $specification, Item $specificationItem)
    {
        $specificationItemData = $specification->items()
            ->wherePivot('item_id', $specificationItem->id)->first();

        return view('range.manufacturing-process.specifications.specification-items.edit',
            compact('specification', 'specificationItem', 'specificationItemData'));
    }
}
