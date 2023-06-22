<?php

namespace App\Http\Middleware;

use App\Traits\GeneralTrait;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class CheckAdminToken
{

    use GeneralTrait;
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function handle($request, Closure $next)
    {
        $token = JWTAuth::getToken();

        if (!$token) {
            return response()->json(['error' => 'Token not provided'], 401);
        }

        try {
            $user = JWTAuth::parseToken()->authenticate();  // check token if authenticated

        } catch (\Exception $e) {
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                //return response()->json(['error' => 'Token expired'], 401);
                return $this->returnError('E0000','Token expired');

            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                //return response()->json(['error' => 'Token invalid'], 401);
                return $this->returnError('E1000','Token invalid');

            } else {
                //return response()->json(['error' => 'Token error'], 401);
                return $this->returnError('E1100','Token error');
            }
        } catch (\Throwable $e){
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException) {
                return $this->returnError('E0000','Token expired');

            } elseif ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException) {
                return $this->returnError('E1000','Token invalid');

            } else {
                return $this->returnError('E1100','Token error');
            }
        }

        //Auth::login($user, false);

        if(!$user)
            return $this->returnError('EE00',trans('Unauthenticated'));
        return $next($request);
    }
}
