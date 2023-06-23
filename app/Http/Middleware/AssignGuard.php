<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Exceptions\TokenExpiredException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Http\Middleware\BaseMiddleware;


class AssignGuard extends BaseMiddleware
{
    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next , $guard = null): Response
    {

        if($guard != null){
            auth()->shouldUse($guard);
            $token = $request->header('auth-token');
            $request->headers->set('auth-token', (string) $token, true); //append to request
            $request->headers->set('Authorization', 'Bearer '.$token, true);  //append to request
            try{
                  //$user = $this->auth->authenticate($request);  //check authenticted user
               $user = JWTAuth::parseToken($token)->authenticate();
               //$user = JWTAuth::parseToken()->authenticate();

            } catch (TokenExpiredException $e) {
                return  $this -> returnError('401','Unauthenticated user');
            } catch (JWTException $e) {
                return  $this -> returnError('', 'token_invalid '.$e->getMessage());
            }

        }
        return $next($request);
    }
}
