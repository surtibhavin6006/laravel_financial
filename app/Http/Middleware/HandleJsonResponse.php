<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpFoundation\Response;

class HandleJsonResponse
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $request->headers->set('Accept', 'application/json');
        $response = $next($request);

        if ($response instanceof JsonResponse) {
            $original = $response->getData(true);

            $formatted = [
                'success' => $response->isSuccessful(),
                'status' => $response->getStatusCode(),
                'timestamp' => now()->toIso8601String(),
                'message' => $original['message'] ?? '' ,
                'data' => $original['data'] ?? null,
                'errors' => $original['errors'] ?? false,
            ];

            $response->setData($formatted);

            if(!empty($original['access_token'])) {
                $this->setCookie($response, $original['access_token']);
            }

            if(!empty($original['logout'])) {
                $this->logoutCookie($response);
            }
        }

        return $response;
    }

    private function setCookie($res,$token): void
    {
        $cookie = cookie(
            'token',
            $token,
            config('jwt.ttl'),
            '/',
            'localhost',
            true,
            true,
            false,
            'None'
        );

        $res->withCookie($cookie);
    }

    private function logoutCookie($res): void
    {
        $cookie = Cookie::forget('token');
        $res->withCookie($cookie);
    }
}
