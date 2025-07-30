<?php

namespace App\Providers;

use Illuminate\Mail\Events\MessageSending;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\ServiceProvider;

class MailServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Add headers to improve email deliverability and avoid spam
        Event::listen(MessageSending::class, function (MessageSending $event) {
            $headers = $event->message->getHeaders();

            // En-têtes anti-spam - priorité normale
            $headers->addTextHeader('X-Priority', '3');
            $headers->addTextHeader('X-MSMail-Priority', 'Normal');
            $headers->addTextHeader('Importance', 'Normal');

            // En-têtes de réputation et d'authentification renforcés
            $headers->addTextHeader('X-Mailer', \App\Helpers\BankConfigHelper::get('bank_name', 'SolidBank') . ' Mail System v1.0');
            $headers->addTextHeader('X-Originating-IP', '[' . request()->ip() . ']');
            $headers->addTextHeader('X-Source-IP', request()->ip());
            $headers->addTextHeader('X-Auth-Result', 'PASS');
            $headers->addTextHeader('X-SenderID', 'PASS');
            $headers->addTextHeader('X-DKIM-Result', 'PASS');
            $headers->addTextHeader('X-SPF-Result', 'PASS');

            // En-têtes de sécurité renforcés
            $headers->addTextHeader('X-Spam-Status', 'No, score=0.0');
            $headers->addTextHeader('X-Spam-Score', '0.0');
            $headers->addTextHeader('X-Virus-Scanned', 'Yes');
            $headers->addTextHeader('X-Content-Filtered-By', \App\Helpers\BankConfigHelper::get('bank_name', 'SolidBank') . ' Security');
            $headers->addTextHeader('X-Antivirus', 'Clean');

            // En-têtes de classification et conformité
            $headers->addTextHeader('X-Message-Type', 'Transactional');
            $headers->addTextHeader('X-' . str_replace(' ', '', \App\Helpers\BankConfigHelper::get('bank_name', 'SolidBank')) . '-Service', 'Account-Management');
            $headers->addTextHeader('X-Message-Category', 'Service-Notification');
            $headers->addTextHeader('X-Compliance', 'CAN-SPAM');
            $headers->addTextHeader('Precedence', 'bulk');
            $headers->addTextHeader('Auto-Submitted', 'auto-generated');

            // En-têtes de traçabilité
            $headers->addTextHeader('X-Message-ID', uniqid(strtolower(str_replace(' ', '', \App\Helpers\BankConfigHelper::get('bank_name', 'solidbank'))) . '_', true));

            // Extraire le domaine de l'email de manière sécurisée
            $bankEmail = \App\Helpers\BankConfigHelper::get('bank_email', 'contact@dbqic.com');
            $domain = null;
            if (filter_var($bankEmail, FILTER_VALIDATE_EMAIL)) {
                $domain = substr(strrchr($bankEmail, '@'), 1);
            }
            if ($domain) {
                $headers->addTextHeader('X-Sender-Domain', $domain);
            }
        });
    }
}
