<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Specification\UpdateRequest;
use App\Services\Range\ManufacturingProcess\Specification\Service;
use App\Models\Range\Specification;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Specification $specification, UpdateRequest $request, Service $service)
    {
        $newSpecificationNumber = $request->validated();

        $message = $service->update($specification, $newSpecificationNumber);

        return redirect()->route('specifications.index')->with('message', $message);
    }
}
