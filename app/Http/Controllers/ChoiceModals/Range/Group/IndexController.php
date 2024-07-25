<?php

namespace App\Http\Controllers\ChoiceModals\Range\Group;

use App\Http\Controllers\Controller;
use App\Models\Range\Group;

class IndexController extends Controller
{
    public function __invoke()
    {
        $groups = Group::whereNull('group_id')
            ->with('descendants')
            ->get();

        return view('choice-modals.range.group.index', compact('groups'));
    }
}
