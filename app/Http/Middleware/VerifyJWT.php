<?php

namespace App\Http\Middleware;

use Closure;
use JWTAuth;
use Tymon\JWTAuth\Exception\JWTException;

class VerifyJWT
{
     public function handle($request, Closure $next)
     {
           try{
                $user = JWTAuth::toUser($request->input('token'));
           }
           catch(JWTException $e){
                if ($e instanceof Tymon\JWTAuth\Exception\TokenExpiredException) {
                     return response()->json(['token_expired'], $e->getStatusCode());
                }
                elseif ($e instanceof Tymon\JWTAuth\Exception\TokenInvalidException) {
                     return response()->json(['token_invalid'], $e->getStatusCode());
                }
                else {
                     return response()->json(['error' => 'Token is required']);
                }
           }

          return $next($request);
     }
}
