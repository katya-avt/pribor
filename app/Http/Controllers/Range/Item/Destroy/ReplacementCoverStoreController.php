<?php

namespace App\Http\Controllers\Range\Item\Destroy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\Destroy\CoverStoreRequest;
use App\Services\Range\Item\Destroy\CoverModelService;
use App\Models\Range\Item;
use function redirect;

class ReplacementCoverStoreController extends Controller
{
    public function __invoke(Item $item, CoverStoreRequest $request, CoverModelService $service)
    {
        $replacementData = $request->validated();

        $message = $service->store($item, $replacementData);

        return redirect()->route('items.index')->with('message', $message);
    }
}
