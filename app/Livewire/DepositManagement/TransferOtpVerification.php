<?php

namespace App\Livewire\DepositManagement;

use Livewire\Component;

class TransferOtpVerification extends Component
{
    public $otp;
    public $otpSentMessage;
    public $otpErrorMessage;

    protected $listeners = ['otpSent' => 'handleOtpSent'];

    public function mount()
    {
        $this->otp = '';
        $this->otpSentMessage = '';
        $this->otpErrorMessage = '';
    }

    public function handleOtpSent($message)
    {
        $this->otpSentMessage = $message;
    }

    public function resendOtp()
    {
        $this->dispatch('resend-otp');
        $this->otpSentMessage = __('transfers.otp_resent');
    }

    public function render()
    {
        return view('livewire.deposit-management.transfer-otp-verification');
    }
}
