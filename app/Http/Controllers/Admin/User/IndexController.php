<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use function view;

class IndexController extends Controller
{
    public function __invoke()
    {
        $users = User::all();

        return view('admin.users.index', compact('users'));
    }
}
