<?php

namespace App\Http\Controllers\Range\Item\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use function view;

class CreateController extends Controller
{
    public function __invoke(Item $item)
    {
        return view('range.items.manufacturing-process.routes.create', compact('item'));
    }
}
