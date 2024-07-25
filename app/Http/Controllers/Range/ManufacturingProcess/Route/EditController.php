<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Models\Range\Route;
use function view;

class EditController extends Controller
{
    public function __invoke(Route $route)
    {
        return view('range.manufacturing-process.routes.edit', compact('route'));
    }
}
