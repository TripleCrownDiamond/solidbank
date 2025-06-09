<?php

namespace App\Mail;

use App\Models\Account;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AccountStatusNotification extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $user;
    public $account;
    public $actionType;
    public $subject;
    public $emailMessage;
    public $amount;
    public $processedByAdminId;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $account, $actionType, $amount = null, $processedByAdminId = null)
    {
        $this->user = $user;
        $this->account = $account;
        $this->actionType = $actionType;
        $this->amount = $amount;
        $this->processedByAdminId = $processedByAdminId;

        // Set queue delay to avoid rate limiting
        $this->delay(now()->addSeconds(rand(1, 5)));

        // Set subject and message based on action type
        switch ($actionType) {
            case 'activated':
                $this->subject = __('common.account_activated_email_subject');
                $this->emailMessage = __('common.account_activated_email_message');
                break;
            case 'suspended':
                $this->subject = __('common.account_suspended_email_subject');
                $this->emailMessage = __('common.account_suspended_email_message');
                break;
            case 'deleted':
                $this->subject = __('common.account_deleted_email_subject');
                $this->emailMessage = __('common.account_deleted_email_message');
                break;
            case 'card_added':
                $this->subject = __('common.card_added_email_subject');
                $this->emailMessage = __('common.card_added_email_message');
                break;
            case 'card_deleted':
                $this->subject = __('common.card_deleted_email_subject');
                $this->emailMessage = __('common.card_deleted_email_message');
                break;
            case 'card_request_approved':
                $this->subject = __('common.card_request_approved_email_subject');
                $this->emailMessage = __('common.card_request_approved_email_message');
                break;
            case 'wallet_added':
                $this->subject = __('common.wallet_added_email_subject');
                $this->emailMessage = __('common.wallet_added_email_message');
                break;
            case 'wallet_deleted':
                $this->subject = __('common.wallet_deleted_email_subject');
                $this->emailMessage = __('common.wallet_deleted_email_message');
                break;
            case 'email_updated':
                $this->subject = __('common.email_updated_subject');
                $this->emailMessage = __('common.email_updated_message');
                break;
            case 'email_verification':
                $this->subject = __('common.email_verification_subject');
                $this->emailMessage = __('common.email_verification_message');
                break;
            case 'welcome_verification':
                $this->subject = __('common.welcome_verification_subject');
                $this->emailMessage = __('common.welcome_verification_message');
                break;
            case 'deposit_confirmed':
                $this->subject = __('common.deposit_confirmed_email_subject');
                $this->emailMessage = __('common.deposit_confirmed_email_message', ['amount' => $this->amount]);
                break;
            case 'transaction_cancelled':
                $this->subject = __('common.transaction_cancelled_email_subject');
                $this->emailMessage = __('common.transaction_cancelled_email_message', ['amount' => $this->amount]);
                break;
            default:
                $this->subject = __('common.account_status_changed');
                $this->emailMessage = 'Your account status has been updated.';
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
            view: 'emails.account-status-notification',
            with: [
                'user' => $this->user,
                'account' => $this->account,
                'actionType' => $this->actionType,
                'subject' => $this->subject,
                'emailMessage' => $this->emailMessage,
                'amount' => $this->amount,
                'processedByAdminId' => $this->processedByAdminId,
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
