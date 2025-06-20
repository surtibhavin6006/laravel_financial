<?php

namespace App\Http\Controllers;

use App\Services\AuthService;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Cookie;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthController extends Controller implements HasMiddleware
{
    public function __construct(private readonly AuthService $authService)
    {}
    public static function middleware(): array
    {
        return [
            new Middleware('auth:api', except: ['login', 'signup']),
        ];
    }

    /**
     * Get a JWT via given credentials.
     *
     * @return array
     * @throws AuthenticationException
     */
    public function login(): array
    {
        $credentials = request(['email', 'password']);

        if (! $token = auth()->attempt($credentials)) {
            throw new AuthenticationException('username or password is incorrect');
        }

        return $this->respondWithToken($token);
    }

    public function signup(Request $request): array
    {
        $validated  = $request->validate([
           'email' => 'required|string|email|max:255|unique:users',
           'password' => 'required|string|min:6|confirmed',
           'name' => 'required|string|min:6',
        ]);

        $this->authService->signUp($validated);

        return [
            'message' => 'Registered successful',
            'data' => [
                'success' => true,
            ]
        ];
    }

    /**
     * Get the authenticated User.
     *
     * @return array
     */
    public function me(): array
    {
        return [
            'data' => auth()->user(),
            'message' => 'Your Profile'
        ];
    }

    /**
     * Log the user out (Invalidate the token).
     *
     * @return array
     */
    public function logout(): array
    {
        auth()->logout();

        return [
            'message' => 'Successfully logged out',
            'logout' => true
        ];
    }

    /**
     * Refresh a token.
     *
     * @return array
     */
    public function refresh(): array
    {
        return $this->respondWithToken(auth()->refresh());
    }

    /**
     * Get the token array structure.
     *
     * @param string $token
     *
     * @return array
     */
    protected function respondWithToken(string $token): array
    {
        return [
            'data' => [
                'expires_in' => auth()->factory()->getTTL() * 60
            ],
            'message' => "Successfully logged in",
            'access_token' => $token,
        ];
    }
}
