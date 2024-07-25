<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Cover\StoreRequest;
use App\Services\Range\ManufacturingProcess\Cover\Service;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, Service $service)
    {
        $coverNumber = $request->validated();

        $message = $service->store($coverNumber);

        return redirect()->route('covers.index')->with('message', $message);
    }
}
