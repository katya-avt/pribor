<?php

namespace App\Http\Middleware\Custom\NotAddedToOrder;

use Closure;
use Illuminate\Http\Request;
use function abort;

class Specification
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $specification = $request->route('specification');

        if ($specification->added_to_order_at) {
            return abort(404);
        }

        return $next($request);
    }
}
