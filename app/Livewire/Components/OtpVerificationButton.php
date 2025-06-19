<?php

namespace App\Livewire\Components;

use Livewire\Component;

class OtpVerificationButton extends Component
{
    public $otpValue = '';
    public $isOtpValid = false;
    
    protected $listeners = [
        'otp-input-updated' => 'handleOtpInputUpdated',
        'otp-verified' => 'handleOtpVerified',
    ];
    
    public function handleOtpInputUpdated($otp)
    {
        $this->otpValue = $otp;
        $this->isOtpValid = !empty($otp) && strlen($otp) === 6 && is_numeric($otp);
    }
    
    public function handleOtpVerified()
    {
        $this->isOtpValid = true;
    }
    
    public function confirmTransfer()
    {
        if ($this->isOtpValid) {
            $this->dispatch('transfer-confirmed');
        }
    }

    public function render()
    {
        return view('livewire.components.otp-verification-button');
    }
}