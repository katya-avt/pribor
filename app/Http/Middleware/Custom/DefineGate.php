<?php

namespace App\Http\Middleware\Custom;

use App\Models\Users\Permission;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use function auth;

class DefineGate
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
        if (auth()->check()) {
            $permissions = Permission::whereHas('roles', function ($query) {
                $query->where('roles.id', auth()->user()->role_id);
            })->get();

            foreach ($permissions as $permission) {
                Gate::define($permission->name, fn() => true);
            }
        }
        return $next($request);
    }
}
