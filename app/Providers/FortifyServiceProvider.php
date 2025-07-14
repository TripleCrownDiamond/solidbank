<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Désactiver l'enregistrement automatique des routes Fortify
        Fortify::ignoreRoutes();
        
        // Configuration des actions Fortify
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        Fortify::authenticateThrough(function (Request $request) {
            return array_filter([
                config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
                AttemptToAuthenticate::class,
                RedirectIfTwoFactorAuthenticatable::class,
                PrepareAuthenticatedSession::class,
            ]);
        });

        // Limiteurs de connexion
        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());
            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });

        // Vues traditionnelles Fortify (sans Livewire)
        Fortify::loginView(function () {
            return view('auth.login');
        });

        Fortify::registerView(function () {
            return view('auth.register');
        });

        // Vue pour la vérification 2FA
        Fortify::twoFactorChallengeView(function () {
            return view('auth.two-factor-challenge');
        });

        // Vue de vérification d'email
        Fortify::verifyEmailView(function () {
            return view('auth.verify-email');
        });

        // Redirection après connexion pour inclure la locale
        Fortify::redirects('login', function () {
            $locale = app()->getLocale() ?? 'fr';  // Default to 'fr' if locale is not yet set
            return '/' . $locale . '/dashboard';
        });

        // Redirection après déconnexion pour préserver la locale
        Fortify::redirects('logout', function () {
            $locale = app()->getLocale() ?? 'fr';  // Default to 'fr' if locale is not yet set
            return '/' . $locale;
        });

        // Email verification redirect is now handled in config/fortify.php
    }
}
