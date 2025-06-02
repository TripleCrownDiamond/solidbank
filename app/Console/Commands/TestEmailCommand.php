<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewPasswordMail;
use App\Models\User;

class TestEmailCommand extends Command
{
    protected $signature = 'email:test';
    protected $description = 'Send a test email';

    public function handle()
    {
        $this->info('Sending test email...');
        
        try {
            // Créer un utilisateur factice pour le test
            $user = new User([
                'email' => 'test@example.com',
                'name' => 'Test User'
            ]);
            
            // Envoyer l'email de test
            Mail::to($user->email)->send(new NewPasswordMail('test123'));
            
            $this->info('Test email sent successfully!');
        } catch (\Exception $e) {
            $this->error('Error sending test email: ' . $e->getMessage());
            $this->error($e->getTraceAsString());
            return 1;
        }
        
        return 0;
    }
}
