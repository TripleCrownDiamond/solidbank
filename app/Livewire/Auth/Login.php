<?php

namespace App\Livewire\Auth;

use App\Mail\TwoFactorChallengeMail;
use App\Models\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
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
    public $challengeTwoFactorAuthentication = false;
    public $showingRecoveryCodeForm = false;
    public $code;
    public $recovery_code;
    public $email = '';
    public $twoFactorUserId;
    public $password = '';
    public $remember = false;
    public $showPassword = false;
    public $showOtpChallenge = false;
    public $otpCode = '';
    public $pendingUserId;

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
        'code' => ['nullable', 'string'],
        'recovery_code' => ['nullable', 'string'],
        'email' => ['required', 'string', 'email'],
        'password' => ['required', 'string'],
        'otpCode' => ['nullable', 'string', 'size:6'],
    ];

    public function messages()
    {
        return [
            'email.required' => __('login.email_required'),
            'email.email' => __('login.email_invalid'),
            'password.required' => __('login.password_required'),
            'code.required' => __('login.provided_code_invalid'),
            'recovery_code.required' => __('login.recovery_code_invalid'),
            'otpCode.required' => __('login.otp_required'),
            'otpCode.size' => __('login.otp_invalid_length'),
        ];
    }

    public function login(): void
    {
        $this->validate();

        $this->ensureIsNotRateLimited();

        $credentials = $this->only(['email', 'password']);

        $user = Auth::getProvider()->retrieveByCredentials($credentials);

        if (!$user || !Auth::getProvider()->validateCredentials($user, $credentials)) {
            $this->addError('email', __('login.failed'));
            return;
        }

        // Check if 2FA is enabled in config
        $config = Config::first();
        if ($config && $config->two_factor_auth) {
            // Generate and send OTP
            $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            // Store OTP in cache for 10 minutes
            Cache::put('otp_' . $user->id, $otp, 600);

            // Send OTP email
            Mail::to($user->email)->send(new TwoFactorChallengeMail($user, $otp));

            // Set up OTP challenge
            $this->pendingUserId = $user->id;
            $this->showOtpChallenge = true;

            session()->flash('message', __('login.otp_sent'));
            return;
        }

        // Login without 2FA
        Auth::login($user, $this->remember);
        $locale = app()->getLocale() ?? 'fr';
        $this->redirect(route('dashboard', compact('locale')), navigate: true);
    }

    public function verifyOtp(): void
    {
        $this->validate(['otpCode' => 'required|string|size:6']);

        if (!$this->pendingUserId) {
            $this->addError('otpCode', __('login.session_expired'));
            return;
        }

        $storedOtp = Cache::get('otp_' . $this->pendingUserId);

        if (!$storedOtp || $storedOtp !== $this->otpCode) {
            $this->addError('otpCode', __('login.otp_invalid'));
            return;
        }

        // OTP is valid, log in the user
        $user = Auth::getProvider()->retrieveById($this->pendingUserId);

        if (!$user) {
            $this->addError('otpCode', __('login.user_not_found'));
            return;
        }

        // Clear the OTP from cache
        Cache::forget('otp_' . $this->pendingUserId);

        // Login the user
        Auth::login($user, $this->remember);

        $locale = app()->getLocale() ?? 'fr';
        $this->redirect(route('dashboard', compact('locale')), navigate: true);
    }

    public function handleTwoFactorChallenge(): void
    {
        $this->validate();

        /** @var \App\Models\User $user */
        $user = Auth::getProvider()->retrieveById($this->twoFactorUserId);

        if (!$user) {
            $this->addError('code', __('login.provided_code_invalid'));

            return;
        }

        if ($this->showingRecoveryCodeForm) {
            // Vérifier le code de récupération
            if (!$user->isRecoveryCodeValid($this->recovery_code)) {
                $this->addError('recovery_code', __('login.recovery_code_invalid'));

                return;
            }

            // Désactiver les codes de récupération après utilisation
            $user->replaceRecoveryCodes([]);
        } else {
            // Vérifier le code TOTP
            if (!$user->isTwoFactorAuthCodeValid($this->code)) {
                $this->addError('code', __('login.provided_code_invalid'));

                return;
            }
        }

        Auth::login($user, $this->remember);

        $locale = app()->getLocale() ?? 'fr';  // Default to 'fr' if locale is not yet set

        $this->redirect(route('dashboard', compact('locale')), navigate: true);
    }

    public function toggleRecoveryCodeForm(): void
    {
        $this->showingRecoveryCodeForm = !$this->showingRecoveryCodeForm;

        $this->code = '';
        $this->recovery_code = '';
    }

    public function render()
    {
        return view('livewire.auth.login');
    }
}
