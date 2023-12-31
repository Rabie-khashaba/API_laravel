<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPassword
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */


    public function handle($request, Closure $next)
    {
        if( $request->api_password !== env('API_PASSWORD','Rabie123')){   //api_password ==> any name to use in postman
            return response()->json(['message' => 'Unauthenticated.']);
        }

        return $next($request);
    }
}
