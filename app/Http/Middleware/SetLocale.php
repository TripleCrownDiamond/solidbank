<?php

namespace App\Http\Middleware;

use Illuminate\Support\Facades\App;
use Closure;

class SetLocale
{
    public function handle($request, Closure $next)
    {
        // Liste des langues disponibles dans le dossier `lang/`
        $availableLocales = config('app.available_locales');  // Dynamique via `config/app.php`
        $defaultLocale = config('app.fallback_locale');  // Locale de secours

        // Récupérer la locale depuis l'URL ou les paramètres de requête
        $localeFromUrl = $request->segment(1);  // Première partie de l'URL ({locale})
        $localeFromQuery = $request->query('locale');  // Paramètre de requête (?locale=fr)

        // Déterminer la locale à utiliser
        $locale = null;

        if (in_array($localeFromUrl, $availableLocales)) {
            // Prioriser la locale dans l'URL
            $locale = $localeFromUrl;
        } elseif (in_array($localeFromQuery, $availableLocales)) {
            // Sinon, utiliser la locale dans les paramètres de requête
            $locale = $localeFromQuery;
        }

        // Si aucune locale valide n'est trouvée, utiliser la locale par défaut
        if (!$locale) {
            $locale = $defaultLocale;
        }

        // Définir la locale dans l'application
        App::setLocale($locale);
        session(['locale' => $locale]);  // Stocker la locale dans la session

        // Rediriger vers la version préfixée `{locale}` si nécessaire
        if ($localeFromQuery && !$localeFromUrl) {
            // Si la locale est uniquement dans les paramètres de requête, rediriger vers l'URL préfixée
            $path = $request->path();
            return redirect("/$locale/$path");
        }

        return $next($request);
    }
}
