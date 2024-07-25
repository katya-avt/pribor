<?php

namespace App\Http\Controllers\Range\Item\Destroy;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\Destroy\ComponentStoreRequest;
use App\Services\Range\Item\Destroy\SpecificationModelService;
use App\Models\Range\Item;
use function redirect;

class ReplacementComponentStoreController extends Controller
{
    public function __invoke(Item $item, ComponentStoreRequest $request, SpecificationModelService $service)
    {
        $replacementData = $request->validated();

        $message = $service->store($item, $replacementData);

        return redirect()->route('items.index')->with('message', $message);
    }
}
