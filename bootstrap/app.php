<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
   ->withExceptions(function ($exceptions) {
    $exceptions->render(function (\App\Exceptions\ApiException $e, $request) {
        \Log::error($e->getMessage(), [
        'status' => $e->getStatus(),
        'http_code' => $e->getHttpCode(),
        'error_message' => $e->getMessage(),
        'original_error' => $e->getErr(),
        ]);
        return response()->json(
            $e->toArray(),
            $e->getHttpCode()
        );
    });

    $exceptions->render(function (\Throwable $e) {
        \Log::error($e->getMessage(), ['trace' => $e->getTraceAsString()]);
        return response()->json([
            'status' => 'failed',
            'message' => 'Something went wrong. Please try again later.',
        ], 500);
    });
})->create();
