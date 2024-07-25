<?php

namespace App\Http\Middleware\Custom\RouteParamsValidation;

use Closure;
use Illuminate\Http\Request;
use App\Models\Range\Cover as CoverModel;
use function abort;

class Cover
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
        $cover = $request->route('cover');

        $coversArray = CoverModel::pluck('number')->toArray();

        if (in_array($cover->number, $coversArray)) {
            return $next($request);
        } else {
            return abort(404);
        }
    }
}
