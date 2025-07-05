<?php

namespace App\Mail;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\URL;
use App\Mail\Traits\AntiSpamHeaders;

class AccountActivationMail extends Mailable
{
    use Queueable, SerializesModels, AntiSpamHeaders;

    public $user;
    public $activationUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;

        // Générer l'URL d'activation avec signature temporaire
        $this->activationUrl = URL::temporarySignedRoute(
            'account.activate',
            Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'locale' => app()->getLocale(),
                'id' => $user->getKey(),
                'hash' => sha1($user->getEmailForVerification()),
            ]
        );
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('auth.account_activation_subject'),
            using: [
                function ($message) {
                    // Appliquer les en-têtes anti-spam
                    $this->applyAntiSpamHeaders($message, 'activation_email');
                    
                    // Appliquer les en-têtes RGPD
                    $this->applyGdprHeaders($message);
                    
                    // En-têtes spécifiques pour améliorer la délivrabilité
                    $message->getHeaders()
                        ->addTextHeader('X-Email-Category', 'transactional')
                        ->addTextHeader('X-Message-Type', 'email-verification')
                        ->addTextHeader('X-Auto-Response-Suppress', 'All')
                        ->addTextHeader('Precedence', 'bulk')
                        ->addTextHeader('X-Priority', '3')
                        ->addTextHeader('Importance', 'Normal');
                }
            ]
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-activation',
            with: [
                'user' => $this->user,
                'activationUrl' => $this->activationUrl,
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
