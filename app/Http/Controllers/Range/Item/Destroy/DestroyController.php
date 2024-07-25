<?php

namespace App\Http\Controllers\Range\Item\Destroy;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;

class DestroyController extends Controller
{
    public function __invoke(Item $item)
    {
        if ($item->group->isCover()) {
            if ($item->relatedCovers->isNotEmpty()) {
                return redirect()->route('items.confirm-cover-replacement', ['item' => $item->id]);
            }
        } else {
            if ($item->relatedSpecifications->isNotEmpty()) {
                return redirect()->route('items.confirm-component-replacement', ['item' => $item->id]);
            }
        }

        $item->delete();

        return redirect()->route('items.index')->with('message', __('messages.successful_delete'));
    }
}
