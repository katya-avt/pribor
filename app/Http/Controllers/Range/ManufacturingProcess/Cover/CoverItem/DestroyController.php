<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover\CoverItem;

use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use App\Services\Range\ManufacturingProcess\Cover\CoverItem\Service;
use App\Models\Range\Item;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Cover $cover, Item $coverItem, Service $service)
    {
        $message = $service->delete($cover, $coverItem);

        return redirect()->route('covers.show', ['cover' => $cover->number])
            ->with('message', $message);
    }
}
