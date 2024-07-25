<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Exports\CoverExport;
use App\Http\Controllers\Controller;
use App\Models\Range\Cover;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __invoke(Cover $cover)
    {
        return Excel::download(new CoverExport($cover), "cover-{$cover->number}.xlsx");
    }
}
