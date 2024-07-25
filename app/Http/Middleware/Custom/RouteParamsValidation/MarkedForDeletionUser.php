<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use App\Models\Users\User;
use Closure;
use Illuminate\Http\Request;
use function abort;

class MarkedForDeletionUser
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
        $markedForDeletionUsers = User::onlyTrashed();

        if ($markedForDeletionUsers->doesntExist()) {
            return abort(404);
        }

        $markedForDeletionUsersArray = $markedForDeletionUsers->pluck('id')->toArray();

        if (in_array($user->id, $markedForDeletionUsersArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
