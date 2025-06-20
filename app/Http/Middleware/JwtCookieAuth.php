<?php

namespace App\Http\Middleware;

use Closure;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtCookieAuth
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     * @throws AuthenticationException
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->cookie('token');

        if ($token) {
            try{
                $user = auth()->setToken($token)->user();
                $request->merge(['user_id' => $user->id ]);
            } catch (Exception $e) {
                throw new AuthenticationException('Unauthenticated, Token is expired');
            }

            /*JWTAuth::setToken($token);
            try {
                $user = JWTAuth::authenticate();
                auth()->setUser($user);
                $request->merge(['user_id' => $user->id ]);
            } catch (Exception $e) {
                throw new AuthenticationException('Unauthenticated2.');
            }*/
        } else {
            throw new AuthenticationException('Unauthenticated3.');
        }


        return $next($request);
    }
}
