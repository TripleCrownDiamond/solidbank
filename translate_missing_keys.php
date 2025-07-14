<?php

/**
 * Script pour identifier et traduire les clés manquantes dans tous les fichiers de langue
 */

// Langues disponibles
$languages = ['fr', 'en', 'de', 'es', 'pt'];
$langDir = __DIR__ . '/lang';

// Fichiers à traiter
$files = [
    'common.php',
    'auth.php',
    'validation.php',
    'passwords.php',
    'admin.php',
    'transfers.php',
    'messages.php',
    'actions.php',
    'register.php',
    'login.php',
    'profile.php',
    'crypto.php',
    'loan.php',
    'nav.php',
    'welcome.php',
    'pagination.php',
    'forgot-password.php',
    'http-statuses.php'
];

// Traductions automatiques pour les clés communes
$translations = [
    'fr' => [
        'lending_capacity' => 'Nous prêtons jusqu\'à plus de 500 millions d\'euros',
        'lending_capacity_short' => 'Prêts jusqu\'à 500M€',
        'lending_capacity_description' => 'Bénéficiez de notre capacité de financement exceptionnelle pour tous vos projets d\'envergure',
        'comprehensive_crypto_solutions' => 'Solutions crypto complètes',
        'crypto_services' => 'Services Crypto',
        'start_crypto_journey' => 'Commencez votre parcours crypto',
        'global_transfers' => 'Transferts mondiaux',
        'low_fees' => 'Frais réduits',
        'lightning_fast' => 'Ultra rapide',
        'instant_crypto_transfers_desc' => 'Envoyez et recevez des cryptomonnaies rapidement et en toute sécurité',
        'instant_crypto_transfers' => 'Transferts crypto instantanés',
        'private_keys_control' => 'Contrôle des clés privées',
        'multi_signature' => 'Multi-signature',
        'cold_storage' => 'Stockage à froid',
        'secure_storage_desc' => 'Vos cryptomonnaies sont protégées par des mesures de sécurité de niveau bancaire',
        'secure_storage' => 'Stockage sécurisé',
        'other_altcoins' => 'Autres altcoins',
        'ethereum_support' => 'Support Ethereum',
        'bitcoin_support' => 'Support Bitcoin',
        'multi_crypto_support_desc' => 'Gérez plusieurs cryptomonnaies dans un seul portefeuille sécurisé',
        'multi_crypto_support' => 'Support multi-crypto',
        'secure_crypto_wallet_management' => 'Gestion sécurisée de vos portefeuilles de cryptomonnaies avec une technologie de pointe',
        'manage_crypto_assets' => 'Gérez vos actifs crypto'
    ],
    'de' => [
        'lending_capacity' => 'Wir verleihen bis zu über 500 Millionen Euro',
        'lending_capacity_short' => 'Kredite bis zu 500M€',
        'lending_capacity_description' => 'Profitieren Sie von unserer außergewöhnlichen Finanzierungskapazität für alle Ihre Großprojekte',
        'comprehensive_crypto_solutions' => 'Umfassende Krypto-Lösungen',
        'crypto_services' => 'Krypto-Services',
        'start_crypto_journey' => 'Beginnen Sie Ihre Krypto-Reise',
        'global_transfers' => 'Globale Überweisungen',
        'low_fees' => 'Niedrige Gebühren',
        'lightning_fast' => 'Blitzschnell',
        'instant_crypto_transfers_desc' => 'Senden und empfangen Sie Kryptowährungen schnell und sicher',
        'instant_crypto_transfers' => 'Sofortige Krypto-Überweisungen',
        'private_keys_control' => 'Kontrolle der privaten Schlüssel',
        'multi_signature' => 'Multi-Signatur',
        'cold_storage' => 'Cold Storage',
        'secure_storage_desc' => 'Ihre Kryptowährungen sind durch bankentaugliche Sicherheitsmaßnahmen geschützt',
        'secure_storage' => 'Sicherer Speicher',
        'other_altcoins' => 'Andere Altcoins',
        'ethereum_support' => 'Ethereum-Unterstützung',
        'bitcoin_support' => 'Bitcoin-Unterstützung',
        'multi_crypto_support_desc' => 'Verwalten Sie mehrere Kryptowährungen in einer sicheren Wallet',
        'multi_crypto_support' => 'Multi-Krypto-Unterstützung',
        'secure_crypto_wallet_management' => 'Sichere Verwaltung Ihrer Kryptowährungs-Wallets mit modernster Technologie',
        'manage_crypto_assets' => 'Verwalten Sie Ihre Krypto-Assets'
    ],
    'es' => [
        'lending_capacity' => 'Prestamos hasta más de 500 millones de euros',
        'lending_capacity_short' => 'Préstamos hasta 500M€',
        'lending_capacity_description' => 'Benefíciese de nuestra capacidad de financiación excepcional para todos sus proyectos de gran envergadura',
        'comprehensive_crypto_solutions' => 'Soluciones cripto integrales',
        'crypto_services' => 'Servicios Cripto',
        'start_crypto_journey' => 'Comience su viaje cripto',
        'global_transfers' => 'Transferencias globales',
        'low_fees' => 'Tarifas bajas',
        'lightning_fast' => 'Ultrarrápido',
        'instant_crypto_transfers_desc' => 'Envíe y reciba criptomonedas de forma rápida y segura',
        'instant_crypto_transfers' => 'Transferencias cripto instantáneas',
        'private_keys_control' => 'Control de claves privadas',
        'multi_signature' => 'Multi-firma',
        'cold_storage' => 'Almacenamiento en frío',
        'secure_storage_desc' => 'Sus criptomonedas están protegidas por medidas de seguridad de nivel bancario',
        'secure_storage' => 'Almacenamiento seguro',
        'other_altcoins' => 'Otras altcoins',
        'ethereum_support' => 'Soporte Ethereum',
        'bitcoin_support' => 'Soporte Bitcoin',
        'multi_crypto_support_desc' => 'Gestione múltiples criptomonedas en una sola cartera segura',
        'multi_crypto_support' => 'Soporte multi-cripto',
        'secure_crypto_wallet_management' => 'Gestión segura de sus carteras de criptomonedas con tecnología de vanguardia',
        'manage_crypto_assets' => 'Gestione sus activos cripto'
    ],
    'pt' => [
        'lending_capacity' => 'Emprestamos até mais de 500 milhões de euros',
        'lending_capacity_short' => 'Empréstimos até 500M€',
        'lending_capacity_description' => 'Beneficie da nossa capacidade de financiamento excepcional para todos os seus projetos de grande escala',
        'comprehensive_crypto_solutions' => 'Soluções cripto abrangentes',
        'crypto_services' => 'Serviços Cripto',
        'start_crypto_journey' => 'Inicie sua jornada cripto',
        'global_transfers' => 'Transferências globais',
        'low_fees' => 'Taxas baixas',
        'lightning_fast' => 'Ultra rápido',
        'instant_crypto_transfers_desc' => 'Envie e receba criptomoedas de forma rápida e segura',
        'instant_crypto_transfers' => 'Transferências cripto instantâneas',
        'private_keys_control' => 'Controle de chaves privadas',
        'multi_signature' => 'Multi-assinatura',
        'cold_storage' => 'Armazenamento frio',
        'secure_storage_desc' => 'Suas criptomoedas são protegidas por medidas de segurança de nível bancário',
        'secure_storage' => 'Armazenamento seguro',
        'other_altcoins' => 'Outras altcoins',
        'ethereum_support' => 'Suporte Ethereum',
        'bitcoin_support' => 'Suporte Bitcoin',
        'multi_crypto_support_desc' => 'Gerencie múltiplas criptomoedas em uma carteira segura',
        'multi_crypto_support' => 'Suporte multi-cripto',
        'secure_crypto_wallet_management' => 'Gestão segura de suas carteiras de criptomoedas com tecnologia de ponta',
        'manage_crypto_assets' => 'Gerencie seus ativos cripto'
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

function getAllKeys($data, $prefix = '') {
    $keys = [];
    foreach ($data as $key => $value) {
        $fullKey = $prefix ? $prefix . '.' . $key : $key;
        if (is_array($value)) {
            $keys = array_merge($keys, getAllKeys($value, $fullKey));
        } else {
            $keys[] = $fullKey;
        }
    }
    return $keys;
}

echo "Analyse des fichiers de langue...\n";

// Analyser chaque fichier
foreach ($files as $file) {
    echo "\nTraitement du fichier: $file\n";
    
    // Collecter toutes les clés de toutes les langues pour ce fichier
    $allKeys = [];
    $languageData = [];
    
    foreach ($languages as $lang) {
        $data = loadLanguageFile($langDir, $lang, $file);
        $languageData[$lang] = $data;
        $keys = getAllKeys($data);
        $allKeys = array_merge($allKeys, $keys);
    }
    
    $allKeys = array_unique($allKeys);
    
    if (empty($allKeys)) {
        echo "  Aucune clé trouvée pour $file\n";
        continue;
    }
    
    echo "  " . count($allKeys) . " clés uniques trouvées\n";
    
    // Vérifier les clés manquantes pour chaque langue
    foreach ($languages as $lang) {
        $data = $languageData[$lang];
        $existingKeys = getAllKeys($data);
        $missingKeys = array_diff($allKeys, $existingKeys);
        
        if (!empty($missingKeys)) {
            echo "  $lang: " . count($missingKeys) . " clés manquantes\n";
            
            // Ajouter les clés manquantes avec des traductions par défaut
            foreach ($missingKeys as $missingKey) {
                // Chercher une traduction prédéfinie
                $translation = null;
                if (isset($translations[$lang]) && isset($translations[$lang][$missingKey])) {
                    $translation = $translations[$lang][$missingKey];
                } else {
                    // Utiliser la version anglaise comme base ou créer une traduction générique
                    $englishData = $languageData['en'];
                    $translation = getNestedValue($englishData, $missingKey);
                    if (!$translation) {
                        $translation = "[TO TRANSLATE] " . $missingKey;
                    }
                }
                
                // Ajouter la clé au tableau de données
                setNestedValue($data, $missingKey, $translation);
            }
            
            // Sauvegarder le fichier mis à jour
            saveLanguageFile($langDir, $lang, $file, $data);
            echo "    Fichier $lang/$file mis à jour\n";
        } else {
            echo "  $lang: Aucune clé manquante\n";
        }
    }
}

function getNestedValue($array, $key) {
    $keys = explode('.', $key);
    $value = $array;
    
    foreach ($keys as $k) {
        if (isset($value[$k])) {
            $value = $value[$k];
        } else {
            return null;
        }
    }
    
    return $value;
}

function setNestedValue(&$array, $key, $value) {
    $keys = explode('.', $key);
    $current = &$array;
    
    foreach ($keys as $k) {
        if (!isset($current[$k])) {
            $current[$k] = [];
        }
        $current = &$current[$k];
    }
    
    $current = $value;
}

echo "\nTraduction terminée!\n";