<?php

namespace App\Http\Controllers\ChoiceModals\Range\ManufacturingProcess\Route;

use App\Http\Controllers\Controller;
use App\Http\Filters\RouteFilter;
use App\Models\Range\Route;
use App\Http\Requests\ChoiceModals\Range\ManufacturingProcess\Route\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(RouteFilter::class, ['queryParams' => array_filter($data)]);

        $routes = Route::filter($filter)->paginate(10);

        return view('choice-modals.range.manufacturing-process.route.index',
            compact('routes', 'data'));
    }
}
