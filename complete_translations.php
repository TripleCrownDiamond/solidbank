<?php

/**
 * Script pour compléter les traductions manquantes avec des traductions appropriées
 */

$langDir = __DIR__ . '/lang';
$languages = ['fr', 'en', 'de', 'es', 'pt'];

// Traductions complètes pour les clés restantes
$completeTranslations = [
    'card_not_found' => [
        'fr' => 'Carte non trouvée',
        'en' => 'Card not found',
        'de' => 'Karte nicht gefunden',
        'es' => 'Tarjeta no encontrada',
        'pt' => 'Cartão não encontrado'
    ],
    'current_card_balance' => [
        'fr' => 'Solde actuel de la carte',
        'en' => 'Current card balance',
        'de' => 'Aktueller Kartensaldo',
        'es' => 'Saldo actual de la tarjeta',
        'pt' => 'Saldo atual do cartão'
    ],
    'discover_benefits' => [
        'fr' => 'Découvrir les avantages',
        'en' => 'Discover benefits',
        'de' => 'Vorteile entdecken',
        'es' => 'Descubrir beneficios',
        'pt' => 'Descobrir benefícios'
    ],
    'get_this_card' => [
        'fr' => 'Obtenir cette carte',
        'en' => 'Get this card',
        'de' => 'Diese Karte erhalten',
        'es' => 'Obtener esta tarjeta',
        'pt' => 'Obter este cartão'
    ],
    'validate' => [
        'fr' => 'Valider',
        'en' => 'Validate',
        'de' => 'Validieren',
        'es' => 'Validar',
        'pt' => 'Validar'
    ],
    'verifying' => [
        'fr' => 'Vérification en cours...',
        'en' => 'Verifying...',
        'de' => 'Überprüfung läuft...',
        'es' => 'Verificando...',
        'pt' => 'Verificando...'
    ],
    'view_details' => [
        'fr' => 'Voir les détails',
        'en' => 'View details',
        'de' => 'Details anzeigen',
        'es' => 'Ver detalles',
        'pt' => 'Ver detalhes'
    ],
    'rib_details' => [
        'fr' => 'Détails du RIB',
        'en' => 'RIB details',
        'de' => 'RIB-Details',
        'es' => 'Detalles del RIB',
        'pt' => 'Detalhes do RIB'
    ],
    'bank' => [
        'fr' => 'Banque',
        'en' => 'Bank',
        'de' => 'Bank',
        'es' => 'Banco',
        'pt' => 'Banco'
    ],
    'card_request_cancelled_admin_subject' => [
        'fr' => 'Demande de carte annulée par l\'administrateur',
        'en' => 'Card request cancelled by administrator',
        'de' => 'Kartenanfrage vom Administrator storniert',
        'es' => 'Solicitud de tarjeta cancelada por el administrador',
        'pt' => 'Solicitação de cartão cancelada pelo administrador'
    ],
    'card_request_cancelled_admin_message' => [
        'fr' => 'Votre demande de carte bancaire a été annulée par votre gestionnaire. Pour plus d\'informations, veuillez contacter notre service client.',
        'en' => 'Your bank card request has been cancelled by your manager. For more information, please contact our customer service.',
        'de' => 'Ihre Bankkartenanfrage wurde von Ihrem Manager storniert. Für weitere Informationen wenden Sie sich bitte an unseren Kundenservice.',
        'es' => 'Su solicitud de tarjeta bancaria ha sido cancelada por su gerente. Para más información, póngase en contacto con nuestro servicio al cliente.',
        'pt' => 'Sua solicitação de cartão bancário foi cancelada pelo seu gerente. Para mais informações, entre em contato com nosso atendimento ao cliente.'
    ],
    'subscribing' => [
        'fr' => 'Inscription en cours...',
        'en' => 'Subscribing...',
        'de' => 'Anmeldung läuft...',
        'es' => 'Suscribiendo...',
        'pt' => 'Inscrevendo...'
    ],
    'professional_crypto_management' => [
        'fr' => 'Gestion professionnelle de vos investissements en cryptomonnaies',
        'en' => 'Professional management of your cryptocurrency investments',
        'de' => 'Professionelle Verwaltung Ihrer Kryptowährungs-Investitionen',
        'es' => 'Gestión profesional de sus inversiones en criptomonedas',
        'pt' => 'Gestão profissional de seus investimentos em criptomoedas'
    ],
    'crypto_wallet_creation' => [
        'fr' => 'Création de portefeuille crypto',
        'en' => 'Crypto Wallet Creation',
        'de' => 'Krypto-Wallet-Erstellung',
        'es' => 'Creación de cartera cripto',
        'pt' => 'Criação de carteira cripto'
    ],
    'crypto_wallet_creation_desc' => [
        'fr' => 'Créez facilement des portefeuilles sécurisés pour vos cryptomonnaies',
        'en' => 'Easily create secure wallets for your cryptocurrencies',
        'de' => 'Erstellen Sie einfach sichere Wallets für Ihre Kryptowährungen',
        'es' => 'Cree fácilmente carteras seguras para sus criptomonedas',
        'pt' => 'Crie facilmente carteiras seguras para suas criptomoedas'
    ],
    'automatic_address_generation' => [
        'fr' => 'Génération automatique d\'adresses',
        'en' => 'Automatic Address Generation',
        'de' => 'Automatische Adressgenerierung',
        'es' => 'Generación automática de direcciones',
        'pt' => 'Geração automática de endereços'
    ],
    'multiple_cryptocurrencies' => [
        'fr' => 'Plusieurs cryptomonnaies',
        'en' => 'Multiple Cryptocurrencies',
        'de' => 'Mehrere Kryptowährungen',
        'es' => 'Múltiples criptomonedas',
        'pt' => 'Múltiplas criptomoedas'
    ],
    'secure_key_management' => [
        'fr' => 'Gestion sécurisée des clés',
        'en' => 'Secure Key Management',
        'de' => 'Sichere Schlüsselverwaltung',
        'es' => 'Gestión segura de claves',
        'pt' => 'Gestão segura de chaves'
    ],
    'crypto_transfers' => [
        'fr' => 'Transferts crypto',
        'en' => 'Crypto Transfers',
        'de' => 'Krypto-Überweisungen',
        'es' => 'Transferencias cripto',
        'pt' => 'Transferências cripto'
    ],
    'crypto_transfers_desc' => [
        'fr' => 'Effectuez des transferts de cryptomonnaies rapides et sécurisés',
        'en' => 'Perform fast and secure cryptocurrency transfers',
        'de' => 'Führen Sie schnelle und sichere Kryptowährungs-Überweisungen durch',
        'es' => 'Realice transferencias de criptomonedas rápidas y seguras',
        'pt' => 'Realize transferências de criptomoedas rápidas e seguras'
    ],
    'instant_crypto_transactions' => [
        'fr' => 'Transactions crypto instantanées',
        'en' => 'Instant Crypto Transactions',
        'de' => 'Sofortige Krypto-Transaktionen',
        'es' => 'Transacciones cripto instantáneas',
        'pt' => 'Transações cripto instantâneas'
    ],
    'competitive_fees' => [
        'fr' => 'Frais compétitifs',
        'en' => 'Competitive Fees',
        'de' => 'Wettbewerbsfähige Gebühren',
        'es' => 'Tarifas competitivas',
        'pt' => 'Taxas competitivas'
    ],
    'contact_info' => [
        'fr' => 'Informations de contact',
        'en' => 'Contact Information',
        'de' => 'Kontaktinformationen',
        'es' => 'Información de contacto',
        'pt' => 'Informações de contato'
    ]
];

function loadLanguageFile($langDir, $lang, $file) {
    $filePath = $langDir . '/' . $lang . '/' . $file;
    if (file_exists($filePath)) {
        return include $filePath;
    }
    return [];
}

function saveLanguageFile($langDir, $lang, $file, $data) {
    $filePath = $langDir . '/' . $lang . '/' . $file;
    $content = "<?php\n\nreturn [\n";
    
    foreach ($data as $key => $value) {
        if (is_array($value)) {
            $content .= "    '$key' => [\n";
            foreach ($value as $subKey => $subValue) {
                if (is_array($subValue)) {
                    $content .= "        '$subKey' => [\n";
                    foreach ($subValue as $subSubKey => $subSubValue) {
                        $escapedValue = addslashes($subSubValue);
                        $content .= "            '$subSubKey' => '$escapedValue',\n";
                    }
                    $content .= "        ],\n";
                } else {
                    $escapedValue = addslashes($subValue);
                    $content .= "        '$subKey' => '$escapedValue',\n";
                }
            }
            $content .= "    ],\n";
        } else {
            $escapedValue = addslashes($value);
            $content .= "    '$key' => '$escapedValue',\n";
        }
    }
    
    $content .= "];\n";
    
    file_put_contents($filePath, $content);
}

echo "Complétion des traductions manquantes...\n";

$files = ['common.php', 'crypto.php', 'loan.php'];

foreach ($files as $file) {
    echo "\nTraitement du fichier: $file\n";
    
    foreach ($languages as $lang) {
        $data = loadLanguageFile($langDir, $lang, $file);
        $updated = false;
        
        foreach ($data as $key => $value) {
            // Vérifier si la valeur commence par '[TO TRANSLATE]'
            if (is_string($value) && strpos($value, '[TO TRANSLATE]') === 0) {
                $cleanKey = str_replace('[TO TRANSLATE] ', '', $value);
                
                if (isset($completeTranslations[$cleanKey]) && isset($completeTranslations[$cleanKey][$lang])) {
                    $data[$key] = $completeTranslations[$cleanKey][$lang];
                    $updated = true;
                    echo "  $lang: Traduit '$cleanKey'\n";
                }
            }
        }
        
        if ($updated) {
            saveLanguageFile($langDir, $lang, $file, $data);
            echo "  Fichier $lang/$file mis à jour\n";
        }
    }
}

echo "\nTraductions complétées!\n";