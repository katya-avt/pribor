<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use App\Models\Range\Item;
use function view;

class EditController extends Controller
{
    public function __invoke(Cover $cover, Item $coverItem)
    {
        $coverItemData = $cover->items()->wherePivot('item_id', $coverItem->id)->first();

        return view('range.manufacturing-process.covers.cover-items.edit',
            compact('cover', 'coverItem', 'coverItemData'));
    }
}
