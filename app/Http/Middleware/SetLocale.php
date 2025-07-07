<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Log pour les requêtes d'activation
        if (str_contains($request->path(), 'activate')) {
            Log::info('SetLocale middleware processing activation request', [
                'url' => $request->fullUrl(),
                'path' => $request->path(),
                'segments' => $request->segments(),
                'method' => $request->method()
            ]);
        }

        // Récupérer dynamiquement les langues disponibles dans le dossier lang/
        $availableLocales = collect(File::directories(base_path('lang')))
            ->map(fn($dir) => basename($dir))
            ->toArray();

        $defaultLocale = config('app.locale', 'fr');
        $sessionLocale = session('locale');

        // Récupérer la locale depuis l'URL ou les paramètres de requête
        $localeFromUrl = $request->segment(1);
        $localeFromQuery = $request->query('locale');

        // Déterminer la locale à utiliser
        $locale = null;

        if (in_array($localeFromUrl, $availableLocales)) {
            $locale = $localeFromUrl;
        } elseif (in_array($localeFromQuery, $availableLocales)) {
            $locale = $localeFromQuery;
        } elseif ($sessionLocale && in_array($sessionLocale, $availableLocales)) {
            $locale = $sessionLocale;
        } else {
            $locale = $defaultLocale;
        }

        // Définir la locale dans l'application
        App::setLocale($locale);

        // Stocker dans la session
        session(['locale' => $locale]);

        // Stocker aussi dans la configuration pour un accès global
        Config::set('app.current_locale', $locale);

        // Log pour les requêtes d'activation
        if (str_contains($request->path(), 'activate')) {
            Log::info('SetLocale middleware completed', [
                'determined_locale' => $locale,
                'available_locales' => $availableLocales,
                'locale_from_url' => $localeFromUrl,
                'session_locale' => $sessionLocale
            ]);
        }

        return $next($request);
    }
}
