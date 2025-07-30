<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransactionNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $transaction;
    public $type;
    public $emailSubject;
    public $emailMessage;
    public $viewName;
    public $user;
    public $amount;
    public $currency;
    public $attachmentPath;

    /**
     * Create a new message instance.
     */
    public function __construct($emailSubject, $emailMessage, $user, $transaction, $amount, $viewName, $currency = null, $attachmentPath = null)
    {
        $this->emailSubject = $emailSubject;
        $this->emailMessage = $emailMessage;
        $this->user = $user;
        $this->transaction = $transaction;
        $this->amount = $amount;
        $this->viewName = $viewName;
        $this->currency = $currency ?: ($transaction->currency ?: ($transaction->account ? $transaction->account->currency : ($transaction->wallet ? $transaction->wallet->cryptocurrency->symbol : 'EUR')));
        $this->attachmentPath = $attachmentPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->emailSubject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: $this->viewName,
            with: [
                'user' => $this->user,
                'transaction' => $this->transaction,
                'amount' => $this->amount,
                'currency' => $this->currency,
                'emailMessage' => $this->emailMessage,
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
        $attachments = [];
        
        if ($this->attachmentPath && file_exists($this->attachmentPath)) {
            $attachments[] = Attachment::fromPath($this->attachmentPath)
                ->as('bordereau_transaction.pdf')
                ->withMime('application/pdf');
        }
        
        return $attachments;
    }
}