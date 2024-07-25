<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use App\Services\Range\Item\ManufacturingProcess\Cover\Service;
use App\Models\Range\Item;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Item $item, Cover $cover, Service $service)
    {
        $message = $service->delete($item, $cover);

        return redirect()->route('items.covers.index', ['item' => $item->id])
            ->with('message', $message);
    }
}
