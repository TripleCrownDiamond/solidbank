<?php

return [
    /*
    |--------------------------------------------------------------------------
    | En-têtes anti-spam pour améliorer la délivrabilité
    |--------------------------------------------------------------------------
    |
    | Ces en-têtes sont ajoutés automatiquement aux e-mails pour améliorer
    | leur délivrabilité et réduire le risque qu'ils soient marqués comme spam.
    |
    */

    'anti_spam_headers' => [
        // Classification du message
        'X-Message-Category' => 'transactional',
        'X-Content-Class' => 'system-generated',
        'X-Message-Source' => 'automated-system',
        
        // Authentification et sécurité
        'X-Authenticated-Domain' => env('MAIL_FROM_ADDRESS_DOMAIN', 'bred-fin.com'),
        'X-Security-Level' => 'high',
        'X-Mailer' => 'SolidBank-System-v1.0',
        
        // Priorité et importance
        'X-Priority' => '3', // Normal priority
        'X-MSMail-Priority' => 'Normal',
        'Importance' => 'Normal',
        
        // Conformité et réglementation
        'List-Unsubscribe' => '<mailto:unsubscribe@bred-fin.com>',
        'X-Auto-Response-Suppress' => 'All',
        'Precedence' => 'bulk',
        'Auto-Submitted' => 'auto-generated',
        
        // Identification du système
        'X-Entity-ID' => 'BRED-SYSTEM-001',
        'X-Legal-Basis' => 'legitimate-interest',
    ],

    /*
    |--------------------------------------------------------------------------
    | En-têtes spécifiques par type d'e-mail
    |--------------------------------------------------------------------------
    */

    'activation_email' => [
        'X-Business-Purpose' => 'account-activation',
        'X-Message-Purpose' => 'account-verification',
        'X-Delivery-Context' => 'user-requested',
        'X-Entity-ID' => 'BRED-ACTIVATION-001',
    ],

    'password_reset' => [
        'X-Business-Purpose' => 'password-reset',
        'X-Message-Purpose' => 'security-verification',
        'X-Delivery-Context' => 'user-initiated',
        'X-Entity-ID' => 'BRED-PASSWORD-001',
    ],

    'transaction_notification' => [
        'X-Business-Purpose' => 'transaction-notification',
        'X-Message-Purpose' => 'account-activity',
        'X-Delivery-Context' => 'automatic-notification',
        'X-Entity-ID' => 'BRED-TRANSACTION-001',
    ],

    /*
    |--------------------------------------------------------------------------
    | Recommandations DNS pour améliorer la délivrabilité
    |--------------------------------------------------------------------------
    */

    'dns_recommendations' => [
        'spf' => 'v=spf1 include:_spf.hostinger.com ~all',
        'dmarc' => 'v=DMARC1; p=quarantine; rua=mailto:dmarc@bred-fin.com; ruf=mailto:dmarc@bred-fin.com; fo=1',
        'dkim' => 'Configurer DKIM dans le panneau Hostinger',
    ],

    /*
    |--------------------------------------------------------------------------
    | Limites d'envoi recommandées
    |--------------------------------------------------------------------------
    */

    'sending_limits' => [
        'new_domain_hourly' => 100,
        'established_domain_hourly' => 1000,
        'bounce_rate_threshold' => 5, // %
        'complaint_rate_threshold' => 0.1, // %
    ],
];