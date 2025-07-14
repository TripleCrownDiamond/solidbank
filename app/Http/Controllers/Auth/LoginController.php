<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Features;

class LoginController extends Controller
{
    /**
     * Handle an authentication attempt.
     */
    public function authenticate(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ], [
            'email.required' => __('login.email_required'),
            'email.email' => __('login.email_invalid'),
            'password.required' => __('login.password_required'),
        ]);

        $this->ensureIsNotRateLimited($request);

        // Attempt to authenticate the user
        if (Auth::attempt($request->only('email', 'password'), $request->boolean('remember'))) {
            /** @var \App\Models\User $user */
            $user = Auth::user();

            // Check if account is active (except for administrators)
            if (!$user->is_admin) {
                $account = $user->accounts()->first();
                if (!$account || $account->status !== 'ACTIVE') {
                    Auth::logout();
                    return back()->withErrors([
                        'email' => __('auth.account_inactive'),
                    ])->onlyInput('email');
                }
            }

            // Regenerate session for security
            $request->session()->regenerate();

            // Redirect to dashboard with locale
            $locale = app()->getLocale() ?? 'fr';
            return redirect()->route('dashboard', ['locale' => $locale])
                ->with('success', __('login.success'));
        }

        // Authentication failed
        return back()->withErrors([
            'email' => __('login.failed'),
        ])->onlyInput('email');
    }

    /**
     * Ensure the login request is not rate limited.
     */
    protected function ensureIsNotRateLimited(Request $request): void
    {
        if (!Features::enabled(Features::twoFactorAuthentication())) {
            return;
        }

        // Check if user is rate limited due to multiple attempts
        $user = Auth::getProvider()->retrieveByCredentials(['email' => $request->email]);
        if ($user && $user->two_factor_confirmed) {
            $limiter = app(RateLimiter::class);
            if ($limiter->tooManyAttempts($request->email, 5)) {
                throw ValidationException::withMessages([
                    'email' => __('auth.throttle', [
                        'seconds' => $limiter->availableIn($request->email),
                        'minutes' => ceil($limiter->availableIn($request->email) / 60),
                    ]),
                ])->status(429);
            }
        }
    }
}