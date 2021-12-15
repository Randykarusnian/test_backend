<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

use Exception;
use Tymon\JWTAuth\Facades\JWTAuth;

use App\Http\Controllers\Controller;

class JwtMiddleware
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
        try {
            $user = JWTAuth::parseToken()->authenticate();
        } catch (Exception $e) {
            $base = new Controller;
            if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenInvalidException){
                // return \Response::json([
                //     'success' => false,
                //     'code'  => -999999,
                //     "message" => "Token is Invalid",
                //     "data" => null
                // ], 500);
                return $base->base_response("-9999999", "Token is Invalid");
            }else if ($e instanceof \Tymon\JWTAuth\Exceptions\TokenExpiredException){
                // return \Response::json([
                //     'success' => false,
                //     'code'  => -999999,
                //     "message" => "Token Expired",
                //     "data" => null
                // ], 500);
                return $base->base_response("-9999999", "Token Expired");
            }else{
                return $base->base_response("-9999999", "Authorization Token not found");
                // return response()->json(['status' => 'Authorization Token not found']);
            }
        }
        return $next($request);
    }
}
