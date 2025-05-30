<?php

namespace App\Providers;

use App\Mail\VerifyEmail as VerifyEmailMailable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Carbon;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
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
        VerifyEmail::toMailUsing(function ($notifiable) {
            // Forcer la locale à 'fr' si elle n'est pas disponible
            $locale = $this->getCurrentLocale();

            // Créer l'URL avec tous les paramètres requis
            $verificationUrl = URL::temporarySignedRoute(
                'verification.verify',
                Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
                [
                    'locale' => $locale,
                    'id' => $notifiable->getKey(),
                    'hash' => sha1($notifiable->getEmailForVerification()),
                ]
            );

            return new \App\Mail\VerifyEmail($notifiable, $verificationUrl);
        });
    }

    /**
     * Récupérer la locale actuelle de manière fiable
     */
    private function getCurrentLocale(): string
    {
        // 1. Vérifier si on a une requête active
        try {
            $request = Request::instance();
            if ($request) {
                // Essayer de récupérer depuis l'URL
                $segments = $request->segments();
                if (!empty($segments)) {
                    $firstSegment = $segments[0];
                    $availableLocales = config('app.available_locales', ['fr', 'en']);
                    if (in_array($firstSegment, $availableLocales)) {
                        return $firstSegment;
                    }
                }

                // Essayer de récupérer depuis la session
                if ($request->hasSession() && $request->session()->has('locale')) {
                    $sessionLocale = $request->session()->get('locale');
                    $availableLocales = config('app.available_locales', ['fr', 'en']);
                    if (in_array($sessionLocale, $availableLocales)) {
                        return $sessionLocale;
                    }
                }
            }
        } catch (\Exception $e) {
            // En cas d'erreur, continuer avec les autres méthodes
        }

        // 2. Vérifier la locale de l'application
        $appLocale = App::getLocale();
        if ($appLocale && $appLocale !== 'en') {
            return $appLocale;
        }

        // 3. Utiliser la locale par défaut du config
        return config('app.locale', 'fr');
    }
}
