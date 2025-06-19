<?php

namespace App\Livewire\Components;

use App\Mail\TransferOtpMail;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class OtpVerification extends Component
{
    public $otp = '';
    public $otpSent = false;
    public $otpVerified = false;
    public $otpMessage = '';

    protected $listeners = [
        'send-otp' => 'handleSendOtp',
        'verify-otp' => 'verifyOtp',
    ];

    protected $rules = [
        'otp' => 'required|digits:6',
    ];

    public function updatedOtp($value)
    {
        $this->dispatch('otp-input-updated', $value);
    }

    public function handleSendOtp()
    {
        Log::info('OtpVerification: handleSendOtp called');
        $this->sendOtp();
    }

    public function sendOtp()
    {
        Log::info('OtpVerification: sendOtp method called');
        /** @var User $user */
        $user = Auth::user();
        if ($user) {
            Log::info('OtpVerification: User found, generating OTP', ['user_id' => $user->id, 'email' => $user->email]);
            $user->two_factor_code = random_int(100000, 999999);
            $user->two_factor_expires_at = now()->addMinutes(10);
            Log::info('OtpVerification: OTP generated', ['otp' => $user->two_factor_code, 'expires_at' => $user->two_factor_expires_at]);

            $user->update([
                'two_factor_code' => $user->two_factor_code,
                'two_factor_expires_at' => $user->two_factor_expires_at
            ]);
            Log::info('OtpVerification: User updated with OTP data');

            Mail::to($user->email)->send(new TransferOtpMail($user, $user->two_factor_code));
            Log::info('OtpVerification: OTP email sent successfully');

            $this->otpSent = true;
            $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.otp_sent_successfully')]);
        } else {
            Log::error('OtpVerification: No authenticated user found');
        }
    }

    public function resendOtp()
    {
        $this->sendOtp();
        $this->dispatch('alert', ['type' => 'success', 'message' => __('messages.otp_sent_successfully')]);
    }

    public function verifyOtp()
    {
        $this->validate();

        /** @var User $user */
        $user = Auth::user();
        if ($user && $user->two_factor_code === $this->otp && $user->two_factor_expires_at > now()) {
            $this->otpVerified = true;
            $this->otpMessage = __('transfers.otp_verified_success');
            Log::info('OtpVerification: OTP verified successfully');

            // Émettre l'événement pour désactiver le bouton précédent et démarrer le processus de transfert
            $this->dispatch('otp-verified');
        } else {
            $this->otpVerified = false;
            $this->otpMessage = __('transfers.otp_invalid');
            $this->addError('otp', __('transfers.otp_invalid'));
            $this->dispatch('alert', ['type' => 'error', 'message' => __('messages.invalid_otp')]);
            Log::warning('OtpVerification: Invalid OTP provided');
        }
    }

    public function confirmTransfer()
    {
        if ($this->otpVerified) {
            /** @var User $user */
            $user = Auth::user();
            if ($user) {
                $user->resetTwoFactorCode();
                $user->save();
                // Log::info('OtpVerification: Transfer confirmed');

                // Dispatcher un événement pour que le composant parent gère la suite
                $this->dispatch('transfer-confirmed');
            }
        }
    }

    public function render()
    {
        return view('livewire.components.otp-verification');
    }
}
