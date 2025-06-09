<?php

namespace App\Mail;

use App\Models\Transaction;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TransferNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $transaction;
    public $notificationType;
    public $stepTitle;
    public $stepDescription;
    public $adminId;

    /**
     * Create a new message instance.
     */
    public function __construct(
        User $user,
        Transaction $transaction,
        string $notificationType,
        string $stepTitle = null,
        string $stepDescription = null,
        int $adminId = null
    ) {
        $this->user = $user;
        $this->transaction = $transaction;
        $this->notificationType = $notificationType;
        $this->stepTitle = $stepTitle;
        $this->stepDescription = $stepDescription;
        $this->adminId = $adminId;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = match ($this->notificationType) {
            'transfer_created' => __('transfers.transfer_created'),
            'transfer_completed' => __('transfers.transfer_completed'),
            'transfer_received' => __('transfers.transfer_received'),
            'transfer_blocked' => __('transfers.transfer_blocked_at_step', ['step' => $this->stepTitle]),
            'transfer_cancelled' => __('transfers.transfer_cancelled'),
            'step_validation_required' => __('transfers.step_validation_required'),
            default => __('transfers.transfer_notification'),
        };

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
            view: 'emails.transfer-notification',
            with: [
                'user' => $this->user,
                'transaction' => $this->transaction,
                'notificationType' => $this->notificationType,
                'stepTitle' => $this->stepTitle,
                'stepDescription' => $this->stepDescription,
                'adminId' => $this->adminId,
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