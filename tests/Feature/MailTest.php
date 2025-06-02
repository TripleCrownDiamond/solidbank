<?php

namespace Tests\Feature;

use App\Mail\NewPasswordMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class MailTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        
        // Activer le logging pour les tests
        config(['mail.default' => 'log']);
        config(['mail.mailers.log.channel' => 'single']);
    }

    public function test_send_test_email()
    {
        // Désactiver le fake pour les tests réels
        // Mail::fake();


        $email = 'test@example.com';
        $newPassword = 'TestPassword123!';

        try {
            // Envoyer l'email
            Mail::to($email)->send(new NewPasswordMail($newPassword));
            
            // Si on arrive ici, l'email a été envoyé avec succès
            $this->assertTrue(true, 'Email sent successfully');
            
            // Vérifier les logs pour confirmer l'envoi
            $logContent = file_get_contents(storage_path('logs/laravel.log'));
            $this->assertStringContainsString('Message-ID:', $logContent, 'Email logs not found');
            
        } catch (\Exception $e) {
            $this->fail('Failed to send email: ' . $e->getMessage());
        }
    }
}
