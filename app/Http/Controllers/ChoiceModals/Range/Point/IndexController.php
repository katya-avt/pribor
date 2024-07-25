<?php

namespace App\Http\Controllers\ChoiceModals\Range\Point;

use App\Http\Controllers\Controller;
use App\Models\Range\Point;

class IndexController extends Controller
{
    public function __invoke()
    {
        $points = Point::all();
        return view('choice-modals.range.point.index', compact('points'));
    }
}
