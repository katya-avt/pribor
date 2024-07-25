<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Users\Role;
use function view;

class CreateController extends Controller
{
    public function __invoke()
    {
        $roles = Role::all();

        return view('admin.users.create', compact('roles'));
    }
}
