<?php

namespace App\Http\Middleware\Custom\ItemSpecifications;

use Closure;
use Illuminate\Http\Request;
use function abort;

class SpecificationsOnlyForProprietaryItems
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
        $item = $request->route('item');

        if (!$item->itemType->isProprietary()) {
            return abort(404);
        }

        return $next($request);
    }
}
