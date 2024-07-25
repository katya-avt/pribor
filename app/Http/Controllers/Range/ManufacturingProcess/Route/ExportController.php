<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route;

use App\Exports\RouteExport;
use App\Http\Controllers\Controller;
use App\Models\Range\Route;
use Maatwebsite\Excel\Facades\Excel;

class ExportController extends Controller
{
    public function __invoke(Route $route)
    {
        return Excel::download(new RouteExport($route), "route-{$route->number}.xlsx");
    }
}
