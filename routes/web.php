<?php

use App\Http\Controllers\TestMailController;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Route;

// Redirection vers la langue par défaut si aucune langue n'est spécifiée
Route::get('/', function () {
    $defaultLocale = 'fr';  // Langue par défaut
    return redirect("$defaultLocale");
});

// Groupe de routes avec préfixe {locale}
Route::prefix('{locale}')->group(function () {
    // Middleware pour définir la locale en fonction de l'URL
    Route::middleware('set.locale')->group(function () {
        // Page d'accueil
        Route::get('/', function () {
            return view('welcome');
        })->name('home');

        // Route pour le formulaire de connexion

        // Jetstream and authentication routes
        require __DIR__ . '/auth.php';
        require __DIR__ . '/jetstream.php';

        // Route personnalisée pour le dashboard
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboard');
            })->name('dashboard');
        });
    });
});

// Route pour changer la langue
Route::get('/set-locale/{locale}', function ($locale) {
    // Liste des langues disponibles dans le dossier `lang/`
    $availableLocales = array_map('basename', File::directories(lang_path()));

    // Vérifie si la langue demandée est disponible
    if (in_array($locale, $availableLocales)) {
        session(['locale' => $locale]);
        return redirect()->back();
    }

    abort(404);  // Langue non valide
});

// Route de test pour l'envoi d'emails
Route::get('/test-email', [TestMailController::class, 'sendTestEmail']);
