<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
   use App\Http\Middleware\AdminMiddleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
     
  

    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (Throwable $e, $request) {
            // Return JSON for API requests
            if ($request->is('api/*')) {
                $status = method_exists($e, 'getStatusCode') ? $e->getStatusCode() : 500;
                $message = $e->getMessage() ?: 'An error occurred';
                
                // Handle validation errors
                if ($e instanceof \Illuminate\Validation\ValidationException) {
                    return response()->json([
                        'success' => false,
                        'error' => [
                            'code' => 'VALIDATION_ERROR',
                            'message' => 'Validation failed',
                            'errors' => $e->errors()
                        ]
                    ], 422);
                }
                
                // Handle other exceptions
                return response()->json([
                    'success' => false,
                    'error' => [
                        'code' => 'SERVER_ERROR',
                        'message' => config('app.debug') ? $message : 'An error occurred'
                    ]
                ], $status);
            }
        });
    })->create();
