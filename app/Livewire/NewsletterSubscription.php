<?php

namespace App\Livewire;

use App\Models\NewsletterSubscription as NewsletterModel;
use Livewire\Component;
use Illuminate\Validation\Rule;

class NewsletterSubscription extends Component
{
    public $email = '';
    public $isSubscribed = false;
    public $message = '';
    public $messageType = 'success'; // success, error, info

    protected function rules()
    {
        return [
            'email' => [
                'required',
                'email',
                Rule::unique('newsletter_subscriptions', 'email')
                    ->where('is_active', true)
                    ->whereNull('unsubscribed_at')
            ],
        ];
    }

    protected function messages()
    {
        return [
            'email.required' => __('messages.email_required'),
            'email.email' => __('messages.email_invalid'),
            'email.unique' => __('messages.email_already_subscribed'),
        ];
    }

    public function subscribe()
    {
        $this->validate();

        try {
            NewsletterModel::create([
                'email' => $this->email,
                'subscribed_at' => now(),
                'is_active' => true,
            ]);

            $this->isSubscribed = true;
            $this->message = __('messages.newsletter_subscription_success');
            $this->messageType = 'success';
            $this->email = '';

            $this->dispatch('newsletter-subscribed', [
                'message' => $this->message,
                'type' => $this->messageType
            ]);

        } catch (\Exception $e) {
            $this->message = __('messages.newsletter_subscription_error');
            $this->messageType = 'error';
        }
    }

    public function render()
    {
        return view('livewire.newsletter-subscription');
    }
}
