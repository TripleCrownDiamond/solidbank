<?php
namespace App\Livewire\Auth;

use App\Models\Config;
use Illuminate\Support\Facades\Auth;
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

    public function mount()
    {
        // Pré-remplir l'email si fourni dans l'URL ou en session
        $this->email = request('email', session('email', ''));

        // Nettoyer l'email de la session après utilisation
        if (session('email')) {
            session()->forget('email');
        }

        // Handle silent session expired
        if (session('silent_session_expired')) {
            session()->forget('silent_session_expired');
            // Don't show any alert, just continue silently
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

    public function login(): void
    {
        try {
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

                $locale = app()->getLocale() ?? 'fr';
                $this->redirect(route('dashboard', compact('locale')), navigate: true);
            } else {
                $this->dispatch('alert', ['type' => 'error', 'message' => __('login.failed')]);
            }
        } catch (\Illuminate\Session\TokenMismatchException $e) {
            // Gérer l'erreur CSRF silencieusement
            // Rediriger vers la page de login sans afficher d'alerte
            $this->redirect(route('login'));
        } catch (\Exception $e) {
            // Log l'erreur pour le débogage
            \Log::error('Login error: ' . $e->getMessage());
            $this->dispatch('alert', ['type' => 'error', 'message' => __("Une erreur s'est produite. Veuillez réessayer.")]);
        }
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
