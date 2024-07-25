<?php

namespace App\Http\Controllers\Admin\MarkedForDeletion\User;

use App\Http\Controllers\Controller;
use App\Models\Users\User;

class RestoreController extends Controller
{
    public function __invoke(User $user)
    {
        $user->restore();

        return redirect()->route('admin.marked-for-deletion.users.index')
            ->with('message', __('messages.successful_restore'));
    }
}
