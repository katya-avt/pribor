<?php

namespace App\Http\Controllers\PeriodicRequisites\LaborPayment;

use App\Http\Controllers\Controller;
use App\Models\Range\Point;

class EditController extends Controller
{
    public function __invoke(Point $point)
    {
        return view('periodic-requisites.labor-payment.edit', compact('point'));
    }
}
