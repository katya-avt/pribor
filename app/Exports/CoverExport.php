<?php

namespace App\Exports;

use App\Models\Range\Cover;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class CoverExport implements FromView
{
    protected Cover $cover;

    public function __construct(Cover $cover)
    {
        $this->cover = $cover;
    }

    public function view(): View
    {
        $cover = $this->cover;
        $coverData = $this->cover->items->load('group', 'unit');

        return view('exports.cover', compact('cover', 'coverData'));
    }
}
