<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use App\Services\Range\Item\ManufacturingProcess\Specification\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Item $item, Specification $specification, Service $service)
    {
        $message = $service->delete($item, $specification);

        return redirect()->route('items.specifications.index', ['item' => $item->id])
            ->with('message', $message);
    }
}
