<?php

namespace App\Livewire\Auth;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Features;
use Laravel\Fortify\Fortify;
use Laravel\Fortify\TwoFactorChallenge;
use Livewire\Component;

class Login extends Component
{
    public $email = '';
    public $password = '';
    public $remember = false;
    public $showPassword = false;
    public $isSubmitting = false;
    // Variables pour la gestion des erreurs
    public $validationErrors = [];

    public function mount()
    {
        // Pré-remplir l'email si fourni dans l'URL ou en session
        $this->email = request('email', session('email', ''));

        // Nettoyer l'email de la session après utilisation
        if (session('email')) {
            session()->forget('email');
        }
    }

    public function hydrate()
    {
        // S'assurer que la session est active
        if (!session()->isStarted()) {
            session()->start();
        }
    }

    protected function ensureIsNotRateLimited(): void
    {
        if (!Features::enabled(Features::twoFactorAuthentication())) {
            return;
        }

        // Vérifiez si l'utilisateur est limité en raison de tentatives multiples
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $this->email]);
        if ($user && $user->two_factor_confirmed) {
            $limiter = app(RateLimiter::class);
            if ($limiter->tooManyAttempts($this->email, 5)) {
                throw ValidationException::withMessages([
                    'email' => __('auth.throttle', [
                        'seconds' => $limiter->availableIn($this->email),
                        'minutes' => ceil($limiter->availableIn($this->email) / 60),
                    ]),
                ])->status(429);
            }
        }
    }

    protected $rules = [
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
    ];

    public function messages()
    {
        return [
            'email.required' => __('login.email_required'),
            'email.email' => __('login.email_invalid'),
            'password.required' => __('login.password_required'),
        ];
    }

    // Méthode pour réinitialiser les erreurs
    protected function resetErrors()
    {
        // Reset any previous errors
        $this->validationErrors = [];
        $this->resetErrorBag();
    }

    // Méthode utilitaire pour obtenir les données utilisateur nettoyées (sans mots de passe)
    protected function getCleanUserData()
    {
        return [
            'email' => $this->email,
            'remember' => $this->remember,
        ];
    }

    public function login()
    {
        try {
            $this->resetErrors();
            $this->isSubmitting = true;

            $this->validate();
            $this->ensureIsNotRateLimited();

            // Vérifier les informations d'identification et connexion directe (bypass 2FA)
            if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
                /** @var \App\Models\User $user */
                $user = Auth::user();

                // Vérifier si le compte est actif
                $account = $user->accounts()->first();
                if (!$account || $account->status !== 'ACTIVE') {
                    Auth::logout();
                    $this->dispatch('alert', ['type' => 'error', 'message' => __('auth.account_inactive')]);
                    return;
                }

                // Connexion directe sans vérification 2FA
                session()->regenerate();
                $this->dispatch('alert', ['type' => 'success', 'message' => __('login.success')]);

                // Redirection immédiate vers le dashboard
                $locale = app()->getLocale() ?? 'fr';
                return $this->redirectRoute('dashboard', ['locale' => $locale]);
            } else {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('login.failed')]);
            }
        } catch (\Illuminate\Validation\ValidationException $e) {
            $this->validationErrors = $e->errors();
            $firstError = collect($e->errors())->flatten()->first();
            $errorMessage = __('login.validation_error_message');
            if ($firstError) {
                $errorMessage .= ' ' . $firstError;
            }
            $this->dispatch('alert', ['type' => 'error', 'message' => $errorMessage]);
            throw $e;
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // Gérer l'erreur CSRF - régénérer la session
            Log::warning('CSRF Token Mismatch in Login', [
                'user_email' => $this->email,
                'session_id' => session()->getId(),
                'csrf_token' => session()->token()
            ]);

            session()->regenerate();
            $this->dispatch('alert', ['type' => 'error', 'message' => __('Session expirée. Veuillez réessayer.')]);
            return;
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            Log::error('Login error: ' . $e->getMessage(), [
                'user_email' => $this->email,
                'user_data' => $this->getCleanUserData(),
                'exception' => $e->getTraceAsString()
            ]);
            $this->dispatch('alert', ['type' => 'error', 'message' => __("Une erreur s'est produite. Veuillez réessayer.")]);
        } finally {
            $this->isSubmitting = false;
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
