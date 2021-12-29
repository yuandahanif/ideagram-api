<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class RequireJWT
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
        if (!$request->hasHeader('access_token')) {
            return response('Unauthorized', 401);
        }
        return $next($request);
    }
}
