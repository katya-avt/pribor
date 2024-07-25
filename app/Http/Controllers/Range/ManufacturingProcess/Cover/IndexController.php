<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Http\Filters\CoverFilter;
use App\Models\Range\Cover;
use App\Http\Requests\Range\ManufacturingProcess\Cover\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(CoverFilter::class, ['queryParams' => array_filter($data)]);

        $covers = Cover::with('items')->filter($filter)->paginate(10);

        return view('range.manufacturing-process.covers.index',
            compact('covers', 'data'));
    }
}
