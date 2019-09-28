<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class JsonRequestMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (
            in_array($request->method(), ['POST', 'PUT', 'PATCH'])
            && $request->isJson()
        ) {
            // $attribute = $request->json()->get('data')['attributes'];
            $attribute = $request->json()->get('data');

            if (array_key_exists('attributes', $attribute)) {
               $data = $request->json()->get('data')['attributes'];
            } elseif (array_key_exists('attribute', $attribute)) {
                $data = $request->json()->get('data')['attribute'];
            } else {
                $data = $request->json()->get('data');
                // dd(is_array($data));
            }
            $request->request->replace(is_array($data) ? $data : []);
        }
        return $next($request);
    }
}
