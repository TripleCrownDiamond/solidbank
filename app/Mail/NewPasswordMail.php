<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class NewPasswordMail extends Mailable
{
    use Queueable, SerializesModels;

    public $newPassword;
    public $userLocale;

    /**
     * Create a new message instance.
     *
     * @param string $newPassword
     * @param string $userLocale
     * @return void
     */
    public function __construct($newPassword, $userLocale = null)
    {
        $this->newPassword = $newPassword;
        $this->userLocale = $userLocale ?: app()->getLocale();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        // Set the locale for this email
        $originalLocale = app()->getLocale();
        App::setLocale($this->userLocale);

        $email = $this
            ->subject(__('forgot-password.new_password_email.title'))
            ->view('emails.new-password')
            ->text('emails.new-password-text');

        // Restore original locale
        App::setLocale($originalLocale);

        return $email;
    }
}
