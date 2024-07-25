<?php

namespace App\Http\Controllers\Admin\MarkedForDeletion\User;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use function view;

class IndexController extends Controller
{
    public function __invoke()
    {
        $users = User::onlyTrashed()->get();

        return view('admin.marked-for-deletion.users.index', compact('users'));
    }
}
