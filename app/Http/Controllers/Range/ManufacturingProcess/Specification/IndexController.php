<?php

namespace App\Http\Controllers\Range\ManufacturingProcess\Specification;

use App\Http\Controllers\Controller;
use App\Http\Filters\SpecificationFilter;
use App\Models\Range\Specification;
use App\Http\Requests\Range\ManufacturingProcess\Specification\IndexRequest;
use function view;

class IndexController extends Controller
{
    public function __invoke(IndexRequest $request)
    {
        $data = $request->validated();
        $filter = app()->make(SpecificationFilter::class, ['queryParams' => array_filter($data)]);

        $specifications = Specification::with('items')->filter($filter)->paginate(10);

        return view('range.manufacturing-process.specifications.index',
            compact('specifications', 'data'));
    }
}
