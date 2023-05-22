<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param  \Closure(Request): (Response|RedirectResponse)  $next
     * @return JsonResponse
     */
    public function handle(Request $request, Closure $next): JsonResponse
    {
        if (Auth::user() &&  Auth::user()->role == 1) {
            return $next($request);
        }

        return response()->json(['You do not have permission to access for this page.']);
    }
}
