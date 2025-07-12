<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Config;
use App\Helpers\BankConfigHelper;
use Illuminate\Support\Facades\DB;

echo "=== TEST DE LA FONCTION DE RÉCUPÉRATION DES DONNÉES CONFIG ===\n\n";

try {
    // Test 1: Connexion base de données
    echo "1. Test connexion base de données...\n";
    DB::connection()->getPdo();
    echo "   ✅ Connexion réussie\n\n";
    
    // Test 2: Vérifier table configs
    echo "2. Vérification table 'configs'...\n";
    $count = Config::count();
    echo "   📊 Nombre d'enregistrements: $count\n\n";
    
    // Test 3: Récupération via modèle
    echo "3. Test Config::first()...\n";
    $configDirect = Config::first();
    if ($configDirect) {
        echo "   ✅ Config trouvée (ID: {$configDirect->id})\n";
        echo "   🏦 Nom: {$configDirect->bank_name}\n";
        echo "   📧 Email: {$configDirect->bank_email}\n";
    } else {
        echo "   ⚠️  Aucune config en base\n";
    }
    echo "\n";
    
    // Test 4: Récupération via helper
    echo "4. Test BankConfigHelper::getConfig()...\n";
    $configHelper = BankConfigHelper::getConfig();
    echo "   🏦 Nom: {$configHelper->bank_name}\n";
    echo "   📧 Email: {$configHelper->bank_email}\n";
    echo "   🎨 Couleur: {$configHelper->brand_color}\n";
    
    if (isset($configHelper->id)) {
        echo "   📊 Source: Base de données\n";
    } else {
        echo "   📊 Source: Configuration par défaut\n";
    }
    echo "\n";
    
    // Test 5: Méthode get spécifique
    echo "5. Test BankConfigHelper::get()...\n";
    $bankName = BankConfigHelper::get('bank_name');
    $loanRate = BankConfigHelper::get('loan_rate', 'Non défini');
    echo "   🏦 bank_name: $bankName\n";
    echo "   💰 loan_rate: $loanRate\n\n";
    
    echo "=== RÉSULTAT ===\n";
    echo "✅ Les fonctions de récupération des données config fonctionnent!\n";
    
} catch (Exception $e) {
    echo "❌ ERREUR: " . $e->getMessage() . "\n";
    echo "📍 Ligne: " . $e->getLine() . "\n";
}

echo "\n=== FIN DU TEST ===\n";