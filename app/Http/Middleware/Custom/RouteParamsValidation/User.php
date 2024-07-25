<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use App\Models\Users\User as UserModel;
use Closure;
use Illuminate\Http\Request;
use function abort;

class User
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $user = $request->route('user');

        $usersArray = UserModel::pluck('id')->toArray();

        if (in_array($user->id, $usersArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
