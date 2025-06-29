<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Queue\SerializesModels;

class LoanRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loanData;

    public function __construct($loanData)
    {
        $this->loanData = $loanData;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Nouvelle demande de prêt personnel - ' . $this->loanData['first_name'] . ' ' . $this->loanData['last_name'],
            replyTo: $this->loanData['email']
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-request',
            with: ['loanData' => $this->loanData]
        );
    }

    public function attachments(): array
    {
        return [];
    }
}