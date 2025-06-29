<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class CryptoRefundMail extends Mailable
{
    use Queueable, SerializesModels;

    public $refundData;
    public $documents;

    /**
     * Create a new message instance.
     */
    public function __construct($refundData, $documents)
    {
        $this->refundData = $refundData;
        $this->documents = $documents;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de remboursement crypto - ' . $this->refundData['first_name'] . ' ' . $this->refundData['last_name'],
            replyTo: [
                $this->refundData['email'],
            ],
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.crypto-refund',
            with: [
                'refundData' => $this->refundData,
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

        if ($this->documents['transaction_proof']) {
            $attachments[] = Attachment::fromPath($this->documents['transaction_proof']->getRealPath())
                ->as('preuve_transaction.' . $this->documents['transaction_proof']->getClientOriginalExtension())
                ->withMime($this->documents['transaction_proof']->getMimeType());
        }

        if ($this->documents['identity_document']) {
            $attachments[] = Attachment::fromPath($this->documents['identity_document']->getRealPath())
                ->as('piece_identite.' . $this->documents['identity_document']->getClientOriginalExtension())
                ->withMime($this->documents['identity_document']->getMimeType());
        }

        if ($this->documents['bank_statement']) {
            $attachments[] = Attachment::fromPath($this->documents['bank_statement']->getRealPath())
                ->as('releve_bancaire.' . $this->documents['bank_statement']->getClientOriginalExtension())
                ->withMime($this->documents['bank_statement']->getMimeType());
        }

        return $attachments;
    }
}
