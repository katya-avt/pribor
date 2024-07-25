<?php

namespace App\Http\Controllers\ChoiceModals\Range\ManufacturingProcess\Cover;

use App\Http\Controllers\Controller;
use App\Http\Filters\CoverFilter;
use App\Models\Range\Cover;
use App\Http\Requests\ChoiceModals\Range\ManufacturingProcess\Cover\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(CoverFilter::class, ['queryParams' => array_filter($data)]);

        $covers = Cover::filter($filter)->paginate(10);

        return view('choice-modals.range.manufacturing-process.cover.index',
            compact('covers', 'data'));
    }
}
