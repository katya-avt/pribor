<?php

namespace App\Exports;

use App\Models\Range\Route;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class RouteExport implements FromView
{
    protected Route $route;

    public function __construct(Route $route)
    {
        $this->route = $route;
    }

    public function view(): View
    {
        $route = $this->route;
        $routeData = $this->route->load('points', 'operations');

        return view('exports.route', compact('route', 'routeData'));
    }
}
