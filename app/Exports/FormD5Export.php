<?php

namespace App\Exports;

use App\Models\Range\Item;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\FromView;

class FormD5Export implements FromView
{
    protected Item $item;

    public function __construct(Item $item)
    {
        $this->item = $item;
    }

    public function view(): View
    {
        $item = $this->item;

        if ($item->group->isCover()) {
            $itemsThatContainItem = Item::join('cover_item', 'items.cover_number', '=', 'cover_item.cover_number')
                ->select(DB::raw('items.id, items.drawing, items.name,
                    cover_item.area * cover_item.consumption AS cnt,
                    cover_item.cover_number AS number'))
                ->where('cover_item.item_id', $item->id)->get();
        } else {
            $itemsThatContainItem = Item::join('specification_item', 'items.specification_number', '=', 'specification_item.specification_number')
                ->select(DB::raw('items.id, items.drawing, items.name, specification_item.cnt, specification_item.specification_number AS number'))
                ->where('specification_item.item_id', $item->id)->get();
        }

        return view('exports.form-d5', compact('item', 'itemsThatContainItem'));
    }
}
