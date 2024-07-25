<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Cover\CoverItem\StoreRequest;
use App\Services\Range\ManufacturingProcess\Cover\CoverItem\Service;
use App\Models\Range\Cover;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(Cover $cover, StoreRequest $request, Service $service)
    {
        $coverItemData = $request->validated();

        $message = $service->store($cover, $coverItemData);

        return redirect()->route('covers.show', ['cover' => $cover->number])
            ->with('message', $message);
    }
}
