<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\StoreRequest;
use App\Services\Admin\User\Service;
use function redirect;

class StoreController extends Controller
{
    public function __invoke(StoreRequest $request, Service $service)
    {
        $userData = $request->validated();

        $message = $service->store($userData);

        return redirect()->route('admin.users.index')->with('message', $message);
    }
}
