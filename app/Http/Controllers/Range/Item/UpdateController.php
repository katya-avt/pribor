<?php

namespace App\Http\Controllers\Range\Item;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\UpdateRequest;
use App\Services\Range\Item\ModifyService;
use App\Models\Range\Item;

class UpdateController extends Controller
{
    public function __invoke(Item $item, UpdateRequest $request, ModifyService $service)
    {
        $data = $request->validated();

        $message = $service->update($item, $data);

        return redirect()->route('items.index')->with('message', $message);
    }
}
