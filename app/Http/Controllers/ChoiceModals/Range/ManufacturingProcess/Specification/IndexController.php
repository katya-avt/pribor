<?php

namespace App\Http\Controllers\ChoiceModals\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Http\Filters\SpecificationFilter;
use App\Models\Range\Specification;
use App\Http\Requests\ChoiceModals\Range\ManufacturingProcess\Specification\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(SpecificationFilter::class, ['queryParams' => array_filter($data)]);

        $specifications = Specification::filter($filter)->paginate(10);

        return view('choice-modals.range.manufacturing-process.specification.index',
            compact('specifications', 'data'));
    }
}
