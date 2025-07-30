<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Mail\Traits\AntiSpamHeaders;

class NewUserPendingNotificationMail extends Mailable
{
    use Queueable, SerializesModels, AntiSpamHeaders;

    public $user;
    public $adminUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
        // URL vers l'administration pour gérer les utilisateurs
        $this->adminUrl = route('admin.users.index');
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('auth.new_user_pending_subject'),
            using: [
                function ($message) {
                    // Appliquer les en-têtes anti-spam
                    $this->applyAntiSpamHeaders($message, 'admin_notification');
                    
                    // Appliquer les en-têtes RGPD
                    $this->applyGdprHeaders($message);
                    
                    // En-têtes spécifiques pour améliorer la délivrabilité
                    $message->getHeaders()
                        ->addTextHeader('X-Email-Category', 'admin-notification')
                        ->addTextHeader('X-Message-Type', 'user-pending')
                        ->addTextHeader('X-Auto-Response-Suppress', 'All')
                        ->addTextHeader('Precedence', 'bulk')
                        ->addTextHeader('X-Priority', '2')
                        ->addTextHeader('Importance', 'High');
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
            view: 'emails.new-user-pending-notification',
            with: [
                'user' => $this->user,
                'adminUrl' => $this->adminUrl,
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