<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Specification\StoreRequest;
use App\Services\Range\ManufacturingProcess\Specification\Service;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, Service $service)
    {
        $specificationNumber = $request->validated();

        $message = $service->store($specificationNumber);

        return redirect()->route('specifications.index')->with('message', $message);
    }
}
