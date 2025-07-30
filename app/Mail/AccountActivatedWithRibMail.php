<?php

namespace App\Mail;

use App\Models\Account;
use App\Models\User;
use App\Models\Rib;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountActivatedWithRibMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $account;
    public $rib;
    public $loginUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, Account $account, Rib $rib)
    {
        $this->user = $user;
        $this->account = $account;
        $this->rib = $rib;
        
        // Generate dynamic login URL with locale
        $this->loginUrl = url('/' . app()->getLocale() . '/login');
        
        // Set queue delay to avoid rate limiting
        $this->delay(now()->addSeconds(rand(1, 5)));
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('auth.account_activated_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.account-activated-with-rib',
            with: [
                'user' => $this->user,
                'account' => $this->account,
                'rib' => $this->rib,
                'loginUrl' => $this->loginUrl,
            ],
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