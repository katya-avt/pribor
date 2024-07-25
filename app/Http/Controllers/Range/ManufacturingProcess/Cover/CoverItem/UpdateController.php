<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Http\Controllers\Controller;
use App\Services\Range\ManufacturingProcess\Cover\CoverItem\Service;
use App\Http\Requests\Range\ManufacturingProcess\Cover\CoverItem\UpdateRequest;
use App\Models\Range\Cover;
use App\Models\Range\Item;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Cover $cover, Item $coverItem, UpdateRequest $request, Service $service)
    {
        $newCoverItemData = $request->validated();

        $message = $service->update($cover, $coverItem, $newCoverItemData);

        return redirect()->route('covers.show', ['cover' => $cover->number])
            ->with('message', $message);
    }
}
