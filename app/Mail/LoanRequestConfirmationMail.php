<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class LoanRequestConfirmationMail extends Mailable
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
            subject: __('loan.confirmation.subject'),
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-request-confirmation',
        );
    }

    public function attachments(): array
    {
        return [];
    }
}