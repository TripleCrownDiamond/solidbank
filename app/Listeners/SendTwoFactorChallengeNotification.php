<?php

namespace App\Listeners;

use App\Mail\TwoFactorChallengeMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;
use Laravel\Fortify\Events\TwoFactorAuthenticationChallenged;

class SendTwoFactorChallengeNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(TwoFactorAuthenticationChallenged $event): void
    {
        Log::info('TwoFactorAuthenticationChallenged event received for user: ' . $event->user->email);

        // Check if the user has a two-factor secret set
        if (empty($event->user->two_factor_secret)) {
            Log::warning('User ' . $event->user->email . ' does not have a two-factor secret set. Skipping 2FA email.');
            return;
        }

        try {
            $provider = app(TwoFactorAuthenticationProvider::class);
            $otp = $event->user->two_factor_code;

            Mail::to($event->user->email)->send(new TwoFactorChallengeMail($event->user, $otp));
            Log::info('Two-factor challenge email sent to: ' . $event->user->email);
        } catch (\Exception $e) {
            Log::error('Failed to send two-factor challenge email to ' . $event->user->email . ': ' . $e->getMessage());
        }
    }
}
