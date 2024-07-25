<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Users\Role;
use App\Models\Users\User;
use function view;

class EditController extends Controller
{
    public function __invoke(User $user)
    {
        $roles = Role::all();

        return view('admin.users.edit', compact('user', 'roles'));
    }
}
