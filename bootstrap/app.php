<?php
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {

        // Sanctum untuk SPA
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Alias middleware role
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
        ]);

        // Kecualikan route API dari CSRF
        $middleware->validateCsrfTokens(except: [
            'api/*'
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {

        // Format error validasi jadi JSON
        $exceptions->renderable(function (
            \Illuminate\Validation\ValidationException $e, $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Data yang dikirim tidak valid.',
                    'errors'  => $e->errors(),
                ], 422);
            }
        });

        // Format 404 jadi JSON
        $exceptions->renderable(function (
            \Illuminate\Database\Eloquent\ModelNotFoundException $e, $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Data tidak ditemukan.'
                ], 404);
            }
        });

        // Format 401 jadi JSON
        $exceptions->renderable(function (
            \Illuminate\Auth\AuthenticationException $e, $request
        ) {
            if ($request->expectsJson()) {
                return response()->json([
                    'message' => 'Silakan login terlebih dahulu.'
                ], 401);
            }
        });
    })->create();