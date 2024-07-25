<?php

namespace App\Http\Controllers\ChoiceModals\Range\Operation;

use App\Http\Controllers\Controller;
use App\Models\Range\Operation;

class IndexController extends Controller
{
    public function __invoke()
    {
        $operations = Operation::whereNull('operation_code')
            ->with('descendants')
            ->get();

        return view('choice-modals.range.operation.index', compact('operations'));
    }
}
