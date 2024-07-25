<?php

namespace App\Exports;

use App\Models\Range\Specification;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class SpecificationExport implements FromView
{
    protected Specification $specification;

    public function __construct(Specification $specification)
    {
        $this->specification = $specification;
    }

    public function view(): View
    {
        $specification = $this->specification;
        $specificationData = $this->specification->items->load('group', 'unit');

        return view('exports.specification',
            compact('specification', 'specificationData'));
    }
}
