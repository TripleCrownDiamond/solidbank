<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Mail;

class Contact extends Component
{
    public $name = '';
    public $email = '';
    public $subject = '';
    public $message = '';

    protected $rules = [
        'name' => 'required|min:3',
        'email' => 'required|email',
        'subject' => 'required|min:5',
        'message' => 'required|min:10',
    ];

    public function submit()
    {
        $this->validate();

        // Ici, vous pouvez ajouter la logique pour envoyer l'email
        // Par exemple :
        // Mail::to('contact@solidbank.com')->send(new ContactFormMail($this->name, $this->email, $this->subject, $this->message));

        session()->flash('message', __('Thank you for your message. We will get back to you soon!'));
        
        $this->reset(['name', 'email', 'subject', 'message']);
    }

    public function render()
    {
        return view('livewire.contact')
            ->layout('layouts.guest');
    }
}
