<?php

namespace App\Http\Controllers\Range\Item\FormD5;

use App\Exports\FormD5Export;
use App\Http\Controllers\Controller;
use App\Models\Range\Item;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __invoke(Item $item)
    {
        return Excel::download(new FormD5Export($item), "formD5-$item->id.xlsx");
    }
}
