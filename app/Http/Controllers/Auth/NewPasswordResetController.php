<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\PasswordResetService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;

class NewPasswordResetController extends Controller
{
    protected $passwordResetService;

    public function __construct(PasswordResetService $passwordResetService)
    {
        $this->passwordResetService = $passwordResetService;
    }

    /**
     * Display the password reset request form.
     */
    public function create()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        // Get the current locale (already set by SetLocale middleware)
        $locale = app()->getLocale();

        // Force set locale from URL if present
        $urlLocale = $request->segment(1);
        if (in_array($urlLocale, ['en', 'fr'])) {
            App::setLocale($urlLocale);
            $locale = $urlLocale;
        }

        // Log password reset attempt
        Log::info('Password reset request initiated', [
            'email' => $request->email,
            'locale' => $locale,
            'ip' => $request->ip(),
            'user_agent' => $request->userAgent()
        ]);

        // Validate the request
        $request->validate([
            'email' => 'required|email'
        ], [
            'email.required' => __('forgot-password.form.email_required'),
            'email.email' => __('forgot-password.form.email_invalid')
        ]);

        $email = $request->email;
        $normalizedEmail = strtolower(trim($email));

        // Find user by email (case-insensitive)
        $user = \App\Models\User::whereRaw('LOWER(email) = ?', [$normalizedEmail])->first();

        if (!$user) {
            Log::warning('Password reset attempted for non-existent user', [
                'email' => $normalizedEmail,
                'ip' => $request->ip()
            ]);

            // Return the same error message to prevent email enumeration
            return back()->withErrors([
                'email' => __('forgot-password.new_password_email.user_not_found')
            ]);
        }

        Log::info('User found for password reset', [
            'user_id' => $user->id,
            'email' => $user->email
        ]);

        try {
            $success = $this->passwordResetService->sendNewPassword($email, $locale);

            if ($success) {
                Log::info('Password reset email sent successfully', [
                    'user_id' => $user->id,
                    'email' => $user->email
                ]);

                return back()->with('status', __('forgot-password.new_password_email.sent'));
            }

            Log::error('Failed to send password reset email', [
                'user_id' => $user->id,
                'email' => $user->email
            ]);

            return back()->withErrors([
                'email' => __('forgot-password.new_password_email.error')
            ]);
        } catch (\Exception $e) {
            Log::error('Exception during password reset process', [
                'user_id' => $user->id ?? null,
                'email' => $email,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return back()->withErrors([
                'email' => __('forgot-password.new_password_email.error')
            ]);
        }
    }
}
