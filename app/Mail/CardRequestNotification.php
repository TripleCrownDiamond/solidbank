<?php

namespace App\Mail;

use App\Models\CardRequest;
use App\Models\Config;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CardRequestNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $cardRequest;
    public $actionType;
    public $subject;
    public $emailMessage;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $cardRequest, $actionType)
    {
        $this->user = $user;
        $this->cardRequest = $cardRequest;
        $this->actionType = $actionType;

        // Set queue delay to avoid rate limiting
        $this->delay(now()->addSeconds(rand(1, 5)));

        // Set subject and message based on action type
        switch ($actionType) {
            case 'new_request':
                $this->subject = __('common.new_card_request_subject');
                $this->emailMessage = __('common.new_card_request_message');
                break;
            case 'request_cancelled':
                $this->subject = __('common.card_request_cancelled_subject');
                $this->emailMessage = __('common.card_request_cancelled_message');
                break;
            case 'request_cancelled_user':
                $this->subject = __('common.card_request_cancelled_user_subject');
                $this->emailMessage = __('common.card_request_cancelled_user_message');
                break;
            case 'request_cancelled_by_admin':
                $this->subject = __('common.card_request_cancelled_by_admin_subject');
                $this->emailMessage = __('common.card_request_cancelled_by_admin_message');
                break;
            default:
                $this->subject = __('common.card_request_notification');
                $this->emailMessage = 'Card request notification.';
                break;
        }
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.card-request-notification',
            with: [
                'user' => $this->user,
                'cardRequest' => $this->cardRequest,
                'actionType' => $this->actionType,
                'subject' => $this->subject,
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
        return [];
    }
}