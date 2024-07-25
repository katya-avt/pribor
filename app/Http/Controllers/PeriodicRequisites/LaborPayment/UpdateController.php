<?php

namespace App\Http\Controllers\PeriodicRequisites\LaborPayment;

use App\Http\Controllers\Controller;
use App\Http\Requests\PeriodicRequisites\LaborPayment\UpdateRequest;
use App\Models\Range\Point;
use App\Services\PeriodicRequisites\LaborPayment\Service;

class UpdateController extends Controller
{
    public function __invoke(Point $point, UpdateRequest $request, Service $service)
    {
        $newBasePayment = $request->validated();

        $message = $service->update($point, $newBasePayment);

        return redirect()->route('periodic-requisites.labor-payment.index')->with('message', $message);
    }
}
