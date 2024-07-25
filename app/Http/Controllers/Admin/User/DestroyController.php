<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Models\Users\User;
use App\Services\Admin\User\Service;
use function redirect;

class DestroyController extends Controller
{
    public function __invoke(User $user, Service $service)
    {
        $message = $service->delete($user);

        return redirect()->route('admin.users.index')->with('message', $message);
    }
}
