<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Route;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function ($router): void {
            Route::prefix('api/v1')
                ->middleware('api')
                ->name('api.v1.')
                ->group(base_path('routes/api.php'));
        }
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->appendToGroup(
            'api',
            [
                App\Http\Middleware\JsonResponseMiddleware::class,
                App\Http\Middleware\CheckApiKeyMiddleware::class,
            ]
        );
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->dontReport(League\OAuth2\Server\Exception\OAuthServerException::class);
    })->create();
