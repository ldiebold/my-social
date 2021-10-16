<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthEndpointsEnabledMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if (env('ENABLE_AUTH_ENDPOINTS')) {
            return $next($request);
        }
        return abort(404);
    }
}
