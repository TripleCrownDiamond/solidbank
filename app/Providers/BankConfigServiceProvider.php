<?php

namespace App\Providers;

use App\Helpers\BankConfigHelper;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class BankConfigServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton('bank.config', function () {
            return new BankConfigHelper();
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Partager la configuration bancaire avec toutes les vues
        View::composer('*', function ($view) {
            try {
                $bankConfig = BankConfigHelper::getConfig();
                $view->with('bankConfig', $bankConfig);
            } catch (\Exception $e) {
                // En cas d'erreur (ex: base de données non disponible), utiliser une config par défaut
                $view->with('bankConfig', null);
            }
        });
    }
}