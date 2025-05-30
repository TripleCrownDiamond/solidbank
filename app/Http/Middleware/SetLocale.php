<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Récupérer dynamiquement les langues disponibles dans le dossier lang/
        $availableLocales = collect(File::directories(base_path('lang')))
            ->map(fn($dir) => basename($dir))
            ->toArray();

        $defaultLocale = config('app.locale', 'fr');

        // Récupérer la locale depuis l'URL ou les paramètres de requête
        $localeFromUrl = $request->segment(1);
        $localeFromQuery = $request->query('locale');

        // Déterminer la locale à utiliser
        $locale = null;

        if (in_array($localeFromUrl, $availableLocales)) {
            $locale = $localeFromUrl;
        } elseif (in_array($localeFromQuery, $availableLocales)) {
            $locale = $localeFromQuery;
        } else {
            $locale = $defaultLocale;
        }

        // Définir la locale dans l'application
        App::setLocale($locale);

        // Stocker dans la session
        session(['locale' => $locale]);

        // Stocker aussi dans la configuration pour un accès global
        Config::set('app.current_locale', $locale);

        return $next($request);
    }
}
