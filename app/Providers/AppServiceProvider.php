<?php

namespace App\Providers;

use App\Mail\VerifyEmail as VerifyEmailMailable;
use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\View;
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
        // Configuration de la vérification d'email
        VerifyEmail::toMailUsing(function ($notifiable) {
            // Récupérer la locale depuis la requête actuelle
            $locale = request()->route('locale') ?? app()->getLocale() ?? 'fr';

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

        // Enregistrer les composants mail personnalisés
        $this->registerMailComponents();
    }

    /**
     * Enregistre les composants mail personnalisés
     */
    protected function registerMailComponents(): void
    {
        // Enregistrer le chemin des composants mail
        Blade::componentNamespace('Mail\Components', 'mail');

        // Ajouter le chemin des vues mail
        View::addNamespace('mail', resource_path('views/vendor/mail'));

        // Définir le thème mail par défaut
        Mail::alwaysFrom(env('MAIL_FROM_ADDRESS', 'hello@example.com'), env('MAIL_FROM_NAME', 'Example'));

        // Définir le chemin des composants mail
        $this->loadViewsFrom(resource_path('views/vendor/mail'), 'mail');

        // Définir le chemin des composants mail pour Laravel
        $this->loadViewsFrom(resource_path('views/vendor/mail/html'), 'mail.html');
        $this->loadViewsFrom(resource_path('views/vendor/mail/text'), 'mail.text');

        // Publier les vues mail si nécessaire
        $this->publishes([
            __DIR__ . '/../../../vendor/laravel/framework/src/Illuminate/Mail/resources/views' => resource_path('views/vendor/mail'),
        ], 'laravel-mail');
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
