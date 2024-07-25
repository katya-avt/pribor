<?php

namespace App\Http\Controllers\Range\Item\CurrentSpecificationsAndRoute;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\Item\CurrentSpecificationsAndRoute\UpdateRequest;
use App\Models\Range\Item;
use App\Services\Range\Item\CurrentSpecificationsAndRoute\Service;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Item $item, UpdateRequest $request, Service $service)
    {
        $newCurrentSpecificationsAndRoute = $request->validated();

        $message = $service->update($item, $newCurrentSpecificationsAndRoute);

        return redirect()->route('items.show', ['item' => $item->id])->with('message', $message);
    }
}
