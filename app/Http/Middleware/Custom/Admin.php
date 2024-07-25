<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use function abort;
use function auth;

class Admin
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
        if (auth()->check() && auth()->user()->isAdmin()) {
            return $next($request);
        }
        return abort(403, 'THIS ACTION IS UNAUTHORIZED.');
    }
}
