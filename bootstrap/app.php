<?php

use App\Http\Middleware\SetLocale;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Alias pour le middleware `set.locale`
        $middleware->alias([
            'set.locale' => SetLocale::class,
        ]);

        Authenticate::redirectUsing(function ($request) {
            return route('login', ['locale' => app()->getLocale()]);
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })
    ->create();
