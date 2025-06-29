<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CryptoRefundMail extends Mailable
{
    use Queueable, SerializesModels;

    public $refundData;
    public $documents;

    /**
     * Create a new message instance.
     */
    public function __construct($refundData, $documents = [])
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
                'subject' => $this->envelope()->subject,
            ],
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
}
