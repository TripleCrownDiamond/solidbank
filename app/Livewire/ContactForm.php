<?php

namespace App\Livewire;

use App\Mail\ContactFormConfirmationMail;
use App\Mail\ContactFormMail;
use App\Models\Config;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class ContactForm extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';
    public $success = false;
    /** @var array<string, string> */
    public $messages;

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email|max:255',
        'subject' => 'required|min:5|max:200',
        'message' => 'required|min:10|max:2000',
    ];

    public function mount()
    {
        $this->messages = [
            'name.required' => __('messages.contact_form.name_required'),
            'name.min' => __('messages.contact_form.name_min'),
            'email.required' => __('messages.contact_form.email_required'),
            'email.email' => __('messages.contact_form.email_email'),
            'subject.required' => __('messages.contact_form.subject_required'),
            'subject.min' => __('messages.contact_form.subject_min'),
            'message.required' => __('messages.contact_form.message_required'),
            'message.min' => __('messages.contact_form.message_min'),
        ];
    }

    public function submit()
    {
        $this->validate();

        try {
            // Récupérer l'email de support depuis la configuration
            $config = Config::first();
            $supportEmail = $config && $config->notification_email ? $config->notification_email : \App\Helpers\BankConfigHelper::get('bank_email', 'support@dbqic.com');

            // Envoyer l'email de notification à l'admin
            Mail::to($supportEmail)->send(new ContactFormMail([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]));

            // Envoyer l'email de confirmation à l'utilisateur
            Mail::to($this->email)->send(new ContactFormConfirmationMail([
                'name' => $this->name,
                'email' => $this->email,
                'subject' => $this->subject,
                'message' => $this->message,
            ]));

            // Réinitialiser le formulaire
            $this->reset(['name', 'email', 'subject', 'message']);
            $this->success = true;

            // Masquer le message de succès après 20 secondes
            $this->dispatch('hide-success-message');
        } catch (\Exception $e) {
            session()->flash('error', __('messages.contact_form.send_error'));
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
