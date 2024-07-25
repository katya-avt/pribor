<?php

namespace App\Http\Controllers\ChoiceModals\Range\MainWarehouse;

use App\Http\Controllers\Controller;
use App\Models\Range\MainWarehouse;

class IndexController extends Controller
{
    public function __invoke()
    {
        $mainWarehouses = MainWarehouse::all();
        return view('choice-modals.range.main-warehouse.index', compact('mainWarehouses'));
    }
}
