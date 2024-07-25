<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Exports\SpecificationExport;
use App\Http\Controllers\Controller;
use App\Models\Range\Specification;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __invoke(Specification $specification)
    {
        return Excel::download(new SpecificationExport($specification), "specification-{$specification->number}.xlsx");
    }
}
