<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification\SpecificationItem;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Specification\SpecificationItem\StoreRequest;
use App\Services\Range\ManufacturingProcess\Specification\SpecificationItem\Service;
use App\Models\Range\Specification;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(Specification $specification, StoreRequest $request, Service $service)
    {
        $specificationItemData = $request->validated();

        $message = $service->store($specification, $specificationItemData);

        return redirect()->route('specifications.show', ['specification' => $specification->number])
            ->with('message', $message);
    }
}
