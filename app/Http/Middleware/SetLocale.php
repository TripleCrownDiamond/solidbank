<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Http\Controllers\LanguageController;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Instancier le contrôleur
        $languageController = new LanguageController();

        // Récupérer les langues disponibles
        $availableLocales = $languageController->getAvailableLanguages();

        // Extraire la locale depuis l'URL ou utiliser la langue par défaut
        $localeFromUrl = $request->segment(1); // Première partie de l'URL (e.g., 'fr', 'en')
        $defaultLocale = config('app.fallback_locale'); // Locale par défaut depuis `config/app.php`

        // Vérifier si la locale extraite est valide
        if (in_array($localeFromUrl, $availableLocales)) {
            app()->setLocale($localeFromUrl);
            session(['locale' => $localeFromUrl]); // Stocker la locale dans la session
        } else {
            // Si la locale n'est pas valide, rediriger vers la locale par défaut
            return redirect($defaultLocale . '/' . $request->path());
        }

        return $next($request);
    }
}