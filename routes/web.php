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

// Alias routes for compatibility
Route::get('/register', function () {
    return redirect()->route('locale.register', ['locale' => app()->getLocale()]);
})->name('register');

Route::get('/login', function () {
    return redirect()->route('locale.login', ['locale' => app()->getLocale()]);
})->name('login');

// Redirection pour les URLs sans préfixe locale
Route::get('/transactions', function () {
    $defaultLocale = session('locale', 'fr');
    return redirect("/$defaultLocale/transactions");
});

Route::get('/dashboard', function () {
    $defaultLocale = session('locale', 'fr');
    return redirect("/$defaultLocale/dashboard");
});

// Groupe de routes avec préfixe {locale}
Route::prefix('{locale}')->group(function () {
    // Middleware pour définir la locale en fonction de l'URL
    Route::middleware('set.locale')->group(function () {
        // Jetstream and authentication routes
        require __DIR__ . '/auth.php';
        require __DIR__ . '/jetstream.php';
        
        // Route d'activation de compte
        Route::get('activate/{id}/{hash}', [\App\Http\Controllers\Auth\AccountActivationController::class, 'activate'])
            ->middleware(['signed'])
            ->name('account.activate');
        
        // Page d'accueil
        Route::get('/', \App\Livewire\Pages\Home::class)->name('home');

        Route::get('/services', \App\Livewire\Pages\Services::class)->name('services');

        Route::get('/contact', \App\Livewire\Pages\Contact::class)->name('contact');

        Route::get('/loan-request', \App\Livewire\Pages\LoanRequest::class)->name('loan-request');
        
        // Route crypto conditionnelle
        if (function_exists('isCryptoEnabled') && isCryptoEnabled()) {
            Route::get('/crypto-refund', \App\Livewire\Pages\CryptoRefund::class)->name('crypto-refund');
        }

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
            Route::get('/transfers/unlock/{transactionId}/{stepId?}', \App\Livewire\DepositManagement\UnlockProgress::class)->name('transfers.unlock');
            Route::get('/transfer/progress/{transferId}', TransferProgress::class)->name('transfer.progress');

            // Routes admin (accessible uniquement aux administrateurs)
            Route::middleware('admin')->group(function () {
                Route::get('/users', function () {
                    return view('dashboard.account-management');
                })->name('admin.users');
                Route::get('/admin/config', function () {
                    return view('admin.config');
                })->name('admin.config');

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
        
        // Route de test pour l'envoi d'emails
        Route::get('/test-email', [TestMailController::class, 'sendTestEmail'])->name('test-email');
    });
});

// Route pour changer la langue
Route::get('/set-locale/{locale}', function ($locale) {
    // Récupérer dynamiquement les langues disponibles (même logique que SetLocale middleware)
    $availableLocales = collect(File::directories(base_path('lang')))
        ->map(fn($dir) => basename($dir))
        ->toArray();
    
    if (in_array($locale, $availableLocales)) {
        session(['locale' => $locale]);
        
        // Analyser l'URL précédente pour remplacer le segment de locale
        $previousUrl = url()->previous();
        $parsedUrl = parse_url($previousUrl);
        $path = $parsedUrl['path'] ?? '/';
        
        // Extraire les segments du chemin et réindexer l'array
        $segments = array_values(array_filter(explode('/', $path)));
        
        // Si le premier segment est une locale, le remplacer
        if (!empty($segments) && isset($segments[0]) && in_array($segments[0], $availableLocales)) {
            $segments[0] = $locale;
        } else {
            // Sinon, ajouter la nouvelle locale au début
            array_unshift($segments, $locale);
        }
        
        // Reconstruire l'URL
        $newPath = '/' . implode('/', $segments);
        $newUrl = ($parsedUrl['scheme'] ?? 'http') . '://' . ($parsedUrl['host'] ?? request()->getHost()) . 
                  (isset($parsedUrl['port']) ? ':' . $parsedUrl['port'] : '') . $newPath;
        
        return redirect($newUrl);
    }
    
    return redirect()->back();
})->name('set-locale');
