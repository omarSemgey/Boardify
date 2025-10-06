<?php

use App\GlobalApiFormatters\ErrorResource;
use App\GlobalExceptions\ApiException;
use App\Http\Middleware\EnsureGuest;
use App\Http\Middleware\RedirectIfAuthenticatedApi;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        channels: __DIR__.'/../routes/channels.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'ensure.guest' => EnsureGuest::class,
        ]);
    })
   ->withExceptions(function ($exceptions) {
        $exceptions->render(function (ApiException $e, $request) {
        \Log::error($e->getMessage(), [
            'status' => $e->getStatus(),
            'http_code' => $e->getHttpCode(),
            'original_error' => $e->getErr(),
        ]);

        return (new ErrorResource([
            'message' => $e->getMessage(),
            'errors' => $e->getErr(),
        ]))->withHttpCode($e->getHttpCode());
    });
})->create();
