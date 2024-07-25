<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UpdateRequest;
use App\Models\Users\User;
use App\Services\Admin\User\Service;
use function redirect;

class UpdateController extends Controller
{
    public function __invoke(User $user, UpdateRequest $request, Service $service)
    {
        $newUserData = $request->validated();

        $message = $service->update($user, $newUserData);

        return redirect()->route('admin.users.index')->with('message', $message);
    }
}
