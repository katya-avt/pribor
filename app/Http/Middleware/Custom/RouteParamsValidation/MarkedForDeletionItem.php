<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use App\Models\Range\Item;
use Closure;
use Illuminate\Http\Request;
use function abort;

class MarkedForDeletionItem
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
        $item = $request->route('item');
        $markedForDeletionItems = Item::onlyTrashed();

        if ($markedForDeletionItems->doesntExist()) {
            return abort(404);
        }

        $markedForDeletionItemsArray = $markedForDeletionItems->pluck('id')->toArray();

        if (in_array($item->id, $markedForDeletionItemsArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
