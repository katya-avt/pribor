<?php

namespace App\Http\Controllers\Range\Item\FormD5;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use Illuminate\Support\Facades\DB;
use function view;

class IndexController extends Controller
{
    public function __invoke(Item $item)
    {
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

        return view('range.items.form-d5.index',
            compact('item', 'itemsThatContainItem'));
    }
}
