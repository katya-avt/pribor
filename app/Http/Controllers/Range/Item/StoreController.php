<?php

namespace App\Http\Controllers\Range\Item;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\StoreRequest;
use App\Services\Range\Item\ModifyService;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, ModifyService $service)
    {
        $data = $request->validated();

        $message = $service->store($data);

        return redirect()->route('items.index')->with('message', $message);
    }
}
