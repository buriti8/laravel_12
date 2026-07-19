<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middleware globales
        $middleware->append(\Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class);

        // Middleware de grupos
        $middleware->appendToGroup('web', \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class);
        $middleware->appendToGroup('web', \Illuminate\Session\Middleware\StartSession::class);
        $middleware->appendToGroup('web', \Illuminate\View\Middleware\ShareErrorsFromSession::class);
        $middleware->appendToGroup('web', \Illuminate\Routing\Middleware\SubstituteBindings::class);

        // Middlewares por alias
        $middleware->alias([
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
            'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
            'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
            'can' => \Illuminate\Auth\Middleware\Authorize::class,
            'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
            'role_or_permission' => \Spatie\Permission\Middleware\RoleOrPermissionMiddleware::class,
        ]);
    })
    ->withExceptions (function (Exceptions $exceptions) {
        $exceptions->dontFlash([
            'password',
            'password_confirmation',
        ]);
    })->create();
