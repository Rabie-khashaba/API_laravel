<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ChangeLanguage
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        app()->setLocale('ar'); // default lang is arabic

        if(isset($request -> lang)  && $request -> lang == 'en')   // lang ==> any name to use in postman
            app()->setLocale('en');
        return $next($request);
    }
}
