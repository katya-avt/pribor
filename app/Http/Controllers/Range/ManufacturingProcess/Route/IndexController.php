<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Http\Filters\RouteFilter;
use App\Models\Range\Route;
use App\Http\Requests\Range\ManufacturingProcess\Route\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(RouteFilter::class, ['queryParams' => array_filter($data)]);

        $routes = Route::with('points', 'operations')->filter($filter)->paginate(10);

        return view('range.manufacturing-process.routes.index',
            compact('routes', 'data'));
    }
}
