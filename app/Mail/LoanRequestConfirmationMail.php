<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\App;

class LoanRequestConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $loanData;
    public $locale;

    /**
     * Create a new message instance.
     */
    public function __construct($loanData, $locale = null)
    {
        $this->loanData = $loanData;
        $this->locale = $locale ?? App::getLocale();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        // Définir temporairement la locale pour la traduction du sujet
        $currentLocale = App::getLocale();
        App::setLocale($this->locale);
        
        $subject = __('loan.confirmation.subject');
        
        // Restaurer la locale précédente
        App::setLocale($currentLocale);
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.loan-request-confirmation',
            with: [
                'loanData' => $this->loanData,
                'locale' => $this->locale,
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

    /**
     * Build the message.
     */
    public function build()
    {
        // Définir la locale pour le rendu du template
        App::setLocale($this->locale);
        
        return $this;
    }
}