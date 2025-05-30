<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider;

class LocaleServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        // Enregistrer un singleton pour gérer la locale
        $this->app->singleton('locale.manager', function ($app) {
            return new class {
                private $currentLocale = null;

                public function setLocale($locale)
                {
                    $this->currentLocale = $locale;
                    App::setLocale($locale);
                    if (session()) {
                        session(['locale' => $locale]);
                    }
                }

                public function getLocale()
                {
                    if ($this->currentLocale) {
                        return $this->currentLocale;
                    }

                    // Essayer de récupérer depuis la session
                    if (session() && session()->has('locale')) {
                        return session('locale');
                    }

                    // Récupérer depuis l'application
                    $appLocale = App::getLocale();
                    if ($appLocale && $appLocale !== 'en') {
                        return $appLocale;
                    }

                    // Valeur par défaut
                    return config('app.locale', 'fr');
                }
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
