<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Models\Range\Specification;
use App\Services\Range\ManufacturingProcess\Specification\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(Specification $specification, Service $service)
    {
        $message = $service->delete($specification);

        return redirect()->route('specifications.index')->with('message', $message);
    }
}
