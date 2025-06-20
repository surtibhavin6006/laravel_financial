<?php

use App\Http\Middleware\HandleJsonResponse;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->append(HandleJsonResponse::class);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, $request) {
            if ($request->expectsJson()) {
                if ($e instanceof ValidationException) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        'errors' => $e->errors(),
                    ], 422);
                }

                if ($e instanceof AuthenticationException) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage(),
                    ], 401);
                }

                if ($e instanceof HttpExceptionInterface) {
                    return response()->json([
                        'success' => false,
                        'message' => $e->getMessage() ?: 'HTTP Error'
                    ], $e->getStatusCode());
                }

                return response()->json([
                    'success' => false,
                    'message' => config('app.debug') ? $e->getMessage() : 'Something went wrong',
                ], 500);
            }

            return null;
        });

    })->create();
