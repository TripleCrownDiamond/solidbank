<?php

return [
    /*
     * |--------------------------------------------------------------------------
     * | En-têtes Anti-Spam de Base
     * |--------------------------------------------------------------------------
     * |
     * | Ces en-têtes sont appliqués à tous les e-mails pour améliorer
     * | la délivrabilité et éviter le classement en spam.
     * |
     */
    'anti_spam_headers' => [
        'X-Mailer' => 'Celesium-Fin Banking System v1.0',
        'X-Priority' => '3',
        'Importance' => 'Normal',
        'X-MSMail-Priority' => 'Normal',
        'X-Auto-Response-Suppress' => 'All',
        'Precedence' => 'list',
        'X-Spam-Status' => 'No',
        'X-Spam-Score' => '0.0',
        'X-Authenticated-Sender' => 'contact@celesium-fin.com',
        'List-Unsubscribe' => '<mailto:unsubscribe@celesium-fin.com>',
        'X-Campaign-Type' => 'transactional',
        'X-Email-Type' => 'system-generated',
        'X-Originating-IP' => '[127.0.0.1]',
        'X-SES-Outgoing' => '2023.12.01-127.0.0.1',
        'Feedback-ID' => 'Celesium-Fin:account-activation:Celesium-Fin.com',
    ],

    /*
     * |--------------------------------------------------------------------------
     * | En-têtes Spécifiques pour l'Activation de Compte
     * |--------------------------------------------------------------------------
     * |
     * | Ces en-têtes sont spécifiquement appliqués aux e-mails d'activation
     * | de compte pour améliorer leur délivrabilité.
     * |
     */
    'activation_email' => [
        'X-Email-Category' => 'account-verification',
        'X-Message-Type' => 'email-verification',
        'X-Content-Type' => 'transactional',
        'X-Security-Level' => 'high',
        'X-Verification-Type' => 'account-activation',
        'X-Business-Category' => 'banking',
        'X-Service-Type' => 'financial-services',
        'X-Authentication-Results' => 'spf=pass smtp.mailfrom=Celesium-Fin.com',
        'X-SenderID' => 'Celesium-Fin-System',
        'X-Entity-Ref-ID' => 'BRED-ACTIVATION-' . date('Ymd'),
        'X-Complaints-To' => 'abuse@celesium-fin.com',
        'X-Report-Abuse' => 'abuse@celesium-fin.com',
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
     * |--------------------------------------------------------------------------
     * | Recommandations DNS pour améliorer la délivrabilité
     * |--------------------------------------------------------------------------
     */
    'dns_recommendations' => [
        'spf' => 'v=spf1 include:_spf.hostinger.com ~all',
        'dmarc' => 'v=DMARC1; p=quarantine; rua=mailto:dmarc@celesium-fin.com; ruf=mailto:dmarc@celesium-fin.com; fo=1',
        'dkim' => 'Configurer DKIM dans le panneau Hostinger',
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Configuration SMTP Améliorée
     * |--------------------------------------------------------------------------
     * |
     * | Configuration recommandée pour améliorer la délivrabilité
     * |
     */
    'smtp_config' => [
        'encryption' => 'ssl',  // Utiliser SSL au lieu de TLS pour le port 465
        'verify_peer' => true,
        'verify_peer_name' => true,
        'allow_self_signed' => false,
        'stream_context_options' => [
            'ssl' => [
                'verify_peer' => true,
                'verify_peer_name' => true,
                'allow_self_signed' => false,
                'SNI_enabled' => true,
            ]
        ]
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Recommandations de Contenu
     * |--------------------------------------------------------------------------
     * |
     * | Mots et phrases à éviter pour réduire le score de spam
     * |
     */
    'content_guidelines' => [
        'avoid_words' => [
            'gratuit', 'urgent', 'promotion', 'offre spéciale', 'limité',
            'cliquez ici', 'agissez maintenant', 'félicitations', 'gagnant',
            'argent facile', 'revenus', 'investissement rapide'
        ],
        'recommended_practices' => [
            'use_https_links' => true,
            'include_unsubscribe_link' => true,
            'maintain_text_html_ratio' => 0.3,
            'avoid_excessive_caps' => true,
            'include_physical_address' => true,
        ]
    ],

    /*
     * |--------------------------------------------------------------------------
     * | Limites d'envoi recommandées
     * |--------------------------------------------------------------------------
     */
    'sending_limits' => [
        'new_domain_hourly' => 100,
        'established_domain_hourly' => 1000,
        'bounce_rate_threshold' => 5,  // %
        'complaint_rate_threshold' => 0.1,  // %
    ],
];
