<?php

namespace App\Http\Middleware;

use Closure;

class JsonApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $request->headers->set('Accept', 'application/vnd.api+json');
        $request->headers->set('Content-Type', 'application/vnd.api+json');

        return $next($request);
    }
}
