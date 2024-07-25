<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use App\Services\Range\ManufacturingProcess\Specification\SpecificationItem\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Specification $specification, Item $specificationItem, Service $service)
    {
        $message = $service->delete($specification, $specificationItem);

        return redirect()->route('specifications.show', ['specification' => $specification->number])
            ->with('message', $message);
    }
}
