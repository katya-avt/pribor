<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Http\Requests\Range\ManufacturingProcess\Cover\UpdateRequest;
use App\Services\Range\ManufacturingProcess\Cover\Service;
use App\Models\Range\Cover;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(Cover $cover, UpdateRequest $request, Service $service)
    {
        $newCoverNumber = $request->validated();

        $message = $service->update($cover, $newCoverNumber);

        return redirect()->route('covers.index')->with('message', $message);
    }
}
