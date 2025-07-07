<?php

use App\Http\Middleware\AdminMiddleware;
use App\Http\Middleware\EnsureEmailIsVerifiedWithLocale;
use App\Http\Middleware\HandleSessionExpired;
use App\Http\Middleware\RedirectIfAuthenticated;
use App\Http\Middleware\SetLocale;
use App\Http\Middleware\UserMiddleware;
use App\Http\Middleware\VerifyCsrfToken;
use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Http\Middleware\ValidatePostSize;
use Illuminate\Http\Middleware\HandleCors;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        // Middlewares globaux pour le groupe 'web'
        $middleware->web(append: [
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            HandleSessionExpired::class,
        ]);

        // Middlewares globaux pour toutes les requêtes
        $middleware->use([
            HandleCors::class,
            ValidatePostSize::class,
        ]);

        // Alias pour le middleware `set.locale`, `guest`, `admin`, `user` et `verified`
        $middleware->alias([
            'set.locale' => SetLocale::class,
            'guest' => RedirectIfAuthenticated::class,
            'admin' => AdminMiddleware::class,
            'user' => UserMiddleware::class,
            'verified' => EnsureEmailIsVerifiedWithLocale::class,
        ]);

        Authenticate::redirectUsing(function ($request) {
            $locale = app()->getLocale() ?? 'fr';
            return route('locale.login', ['locale' => $locale]);
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        $exceptions->render(function (\Symfony\Component\HttpKernel\Exception\NotFoundHttpException $e, $request) {
            // Get current locale from URL or session
            $locale = $request->segment(1);
            $availableLocales = ['fr', 'en'];  // Add your available locales here

            if (!in_array($locale, $availableLocales)) {
                $locale = session('locale', 'fr');  // Default to French
            }

            // Set the application locale
            app()->setLocale($locale);

            return response()->view('errors.404', [], 404);
        });
    })
    ->create();
