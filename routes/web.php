<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;

// Redirection vers la langue par défaut si aucune langue n'est spécifiée
Route::get('/', function () {
    $defaultLocale = 'fr'; // Langue par défaut
    return redirect("$defaultLocale");
});

// Groupe de routes avec préfixe {locale}
Route::prefix('{locale}')->group(function () {
    // Middleware pour définir la locale en fonction de l'URL
    Route::middleware('set.locale')->group(function () {
        // Page d'accueil
        Route::get('/', \App\Livewire\Home::class)->name('home');
        
        // Page À propos
        Route::get('/about', \App\Livewire\About::class)->name('about');
        
        // Page Contact
        Route::get('/contact', \App\Livewire\Contact::class)->name('contact');

        // Dashboard (protégé par authentification)
        Route::middleware([
            'auth:sanctum',
            config('jetstream.auth_session'),
            'verified',
        ])->group(function () {
            Route::get('/dashboard', function () {
                return view('dashboard');
            })->name('dashboard');
        });

        // Autres routes principales ici...
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

    abort(404); // Langue non valide
})->name('set.locale');