<?php

namespace App\Livewire;

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

    protected $rules = [
        'name' => 'required|min:2|max:100',
        'email' => 'required|email|max:255',
        'subject' => 'required|min:5|max:200',
        'message' => 'required|min:10|max:2000',
    ];

    protected $messages = [
        'name.required' => 'Le nom est requis.',
        'name.min' => 'Le nom doit contenir au moins 2 caractères.',
        'email.required' => "L'email est requis.",
        'email.email' => "L'email doit être valide.",
        'subject.required' => 'Le sujet est requis.',
        'subject.min' => 'Le sujet doit contenir au moins 5 caractères.',
        'message.required' => 'Le message est requis.',
        'message.min' => 'Le message doit contenir au moins 10 caractères.',
    ];

    public function submit()
    {
        $this->validate();

        try {
            // Récupérer l'email de support depuis la configuration
            $config = Config::first();
            $supportEmail = $config && $config->notification_email ? $config->notification_email : 'support@Bred Fin.com';

            // Envoyer l'email
            Mail::to($supportEmail)->send(new ContactFormMail([
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
            session()->flash('error', "Une erreur est survenue lors de l'envoi du message. Veuillez réessayer.");
        }
    }

    public function render()
    {
        return view('livewire.contact-form');
    }
}
