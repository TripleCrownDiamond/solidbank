<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Transaction;

class TransferConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $ticketUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(Transaction $transaction, $ticketUrl)
    {
        $this->transaction = $transaction;
        $this->ticketUrl = $ticketUrl;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: __('transfers.transfer_confirmed_email_subject'),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.transfer-confirmation',
            with: [
                'transaction' => $this->transaction,
                'ticketUrl' => $this->ticketUrl,
                'user' => $this->transaction->user,
                'amount' => number_format($this->transaction->amount, 2),
                'currency' => $this->transaction->currency ?? 'EUR'
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