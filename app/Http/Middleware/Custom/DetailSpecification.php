<?php

namespace App\Http\Middleware\Custom;

use Closure;
use Illuminate\Http\Request;
use function abort;

class DetailSpecification
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
        $specification = $request->route('specification');

        if ($specification->relatedItems->isNotEmpty()) {
            if ($specification->relatedItems()->first()->group->isDetail()) {
                if ($specification->items()->count() == 1) {
                    return abort(404);
                }
            }
        }

        return $next($request);
    }
}
