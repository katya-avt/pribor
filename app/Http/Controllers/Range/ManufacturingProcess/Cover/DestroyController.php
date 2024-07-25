<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use App\Services\Range\ManufacturingProcess\Cover\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Cover $cover, Service $service)
    {
        $message = $service->delete($cover);

        return redirect()->route('covers.index')->with('message', $message);
    }
}
