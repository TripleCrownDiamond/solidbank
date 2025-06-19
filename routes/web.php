<?php

use App\Http\Controllers\TestMailController;
use App\Livewire\DepositManagement\TransferProgress;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
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
                return view('dashboard.index');
            })->name('dashboard');

            // Route pour les cartes bancaires (utilisateurs non-admin uniquement)
            Route::middleware('user')->get('/cards', function () {
                return view('dashboard.user-cards');
            })->name('user.cards');

            // Route pour les portefeuilles (utilisateurs non-admin uniquement)
            Route::middleware('user')->get('/wallets', function () {
                return view('dashboard.user-wallets-page');
            })->name('user.wallets');

            // Route pour les transactions (accessible à tous les utilisateurs connectés)
            Route::get('/transactions', function () {
                return view('dashboard.transactions');
            })->name('transactions');

            // Route pour la progression de transfert
            Route::get('/transfer-progress', function () {
                return view('dashboard.transfer-progress');
            })->name('dashboard.transfer-progress');

            // Routes pour les transferts
            Route::get('/transfers/create', function () {
                return redirect()->route('dashboard'); // Or to the actual transfer creation page
            })->name('transfers.create');
            
            Route::get('/transfers/progress', TransferProgress::class)->name('transfers.progress');
            Route::get('/transfers/progress/{transferId}', TransferProgress::class)->name('transfers.progress.resume');

            // Routes admin (accessible uniquement aux administrateurs)
            Route::middleware('admin')->group(function () {
                Route::get('/users', function () {
                    return view('dashboard.account-management');
                })->name('admin.users');

                Route::get('/users/{user}/manage', function ($locale, $user) {
                    Log::info('Route users.manage accessed', ['locale' => $locale, 'user_id' => $user]);

                    try {
                        $user = \App\Models\User::findOrFail($user);
                        Log::info('User found', ['user' => $user->toArray()]);

                        Log::info('Attempting to load view dashboard.user-detail-management');

                        try {
                            $view = view('dashboard.user-detail-management', compact('user'));
                            Log::info('View loaded successfully');
                            return $view;
                        } catch (\Exception $e) {
                            Log::error('Error loading view: ' . $e->getMessage());
                            Log::error('Stack trace: ' . $e->getTraceAsString());
                            throw $e;
                        }
                    } catch (\Exception $e) {
                        Log::error('Error in users.manage route', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
                        throw $e;
                    }
                })->name('users.manage');

                Route::get('/transfer-steps', function () {
                    return view('dashboard.transfer-step-management');
                })->name('transfer-steps');
            });
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
