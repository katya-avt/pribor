<?php

namespace App\Http\Controllers\Range\Item;

use App\Http\Controllers\Controller;

class CreateController extends Controller
{
    public function __invoke()
    {
        return view('range.items.create');
    }
}
