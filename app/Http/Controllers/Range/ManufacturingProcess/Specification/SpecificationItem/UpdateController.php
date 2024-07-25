<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Specification\SpecificationItem\UpdateRequest;
use App\Services\Range\ManufacturingProcess\Specification\SpecificationItem\Service;
use App\Models\Range\Item;
use App\Models\Range\Specification;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Specification $specification, Item $specificationItem,
                             UpdateRequest $request, Service $service)
    {
        $newSpecificationItemData = $request->validated();

        $message = $service->update($specification, $specificationItem, $newSpecificationItemData);

        return redirect()->route('specifications.show', ['specification' => $specification->number])
            ->with('message', $message);
    }
}
