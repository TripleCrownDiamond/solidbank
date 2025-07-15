<?php

/**
 * Script de configuration rapide de la banque
 * Permet de modifier toutes les informations de la banque en une seule commande
 */

// Chargement de la configuration depuis le fichier JSON
$configFile = __DIR__ . '/bank-config.json';
if (!file_exists($configFile)) {
    echo "Erreur: Le fichier bank-config.json n'existe pas.\n";
    echo "Veuillez créer ce fichier avec la configuration de votre banque.\n";
    exit(1);
}

$bankConfig = json_decode(file_get_contents($configFile), true);
if (!$bankConfig) {
    echo "Erreur: Impossible de lire le fichier bank-config.json.\n";
    echo "Vérifiez que le fichier contient un JSON valide.\n";
    exit(1);
}

echo "=== Configuration de la banque ===\n";
echo "Nom: {$bankConfig['bank_name']}\n";
echo "Email: {$bankConfig['bank_email']}\n";
echo "Téléphone: {$bankConfig['bank_phone']}\n";
echo "Adresse: {$bankConfig['bank_address']}\n";
echo "SWIFT: {$bankConfig['bank_swift']}\n";
echo "Préfixe compte: {$bankConfig['account_prefix']}\n";
echo "Logo: {$bankConfig['logo_url']}\n\n";

// Confirmation
echo 'Voulez-vous continuer avec cette configuration ? (y/N): ';
$handle = fopen('php://stdin', 'r');
$line = fgets($handle);
fclose($handle);

if (trim(strtolower($line)) !== 'y') {
    echo "Configuration annulée.\n";
    exit(1);
}

echo "\n=== Mise à jour des fichiers ===\n";

// 1. Mise à jour du ConfigSeeder
echo "Mise à jour du ConfigSeeder...\n";
$configSeederPath = __DIR__ . '/database/seeders/ConfigSeeder.php';
$configSeederContent = file_get_contents($configSeederPath);

// Remplacements dans ConfigSeeder
$configSeederContent = preg_replace(
    "/'bank_name' => '[^']*'/",
    "'bank_name' => '{$bankConfig['bank_name']}'",
    $configSeederContent
);

$configSeederContent = preg_replace(
    "/'bank_email' => '[^']*'/",
    "'bank_email' => '{$bankConfig['bank_email']}'",
    $configSeederContent
);

$configSeederContent = preg_replace(
    "/'bank_phone' => '[^']*'/",
    "'bank_phone' => '{$bankConfig['bank_phone']}'",
    $configSeederContent
);

$configSeederContent = preg_replace(
    "/'bank_address' => '[^']*'/",
    "'bank_address' => '{$bankConfig['bank_address']}'",
    $configSeederContent
);

$configSeederContent = preg_replace(
    "/'bank_swift' => '[^']*'/",
    "'bank_swift' => '{$bankConfig['bank_swift']}'",
    $configSeederContent
);

$configSeederContent = preg_replace(
    "/'account_prefix' => '[^']*'/",
    "'account_prefix' => '{$bankConfig['account_prefix']}'",
    $configSeederContent
);

$configSeederContent = preg_replace(
    "/'logo_url' => '[^']*'/",
    "'logo_url' => '{$bankConfig['logo_url']}'",
    $configSeederContent
);

file_put_contents($configSeederPath, $configSeederContent);
echo "✓ ConfigSeeder mis à jour\n";

// 2. Mise à jour du fichier .env
echo "Mise à jour du fichier .env...\n";
$envPath = __DIR__ . '/.env';
$envContent = file_get_contents($envPath);

// Remplacements dans .env
$envContent = preg_replace(
    '/APP_NAME="[^"]*"/',
    'APP_NAME="' . $bankConfig['bank_name'] . '"',
    $envContent
);

$envContent = preg_replace(
    "/MAIL_FROM_ADDRESS=[^\n]*/",
    'MAIL_FROM_ADDRESS="' . $bankConfig['bank_email'] . '"',
    $envContent
);

$envContent = preg_replace(
    "/MAIL_USERNAME=[^\n]*/",
    'MAIL_USERNAME="' . $bankConfig['bank_email'] . '"',
    $envContent
);

file_put_contents($envPath, $envContent);
echo "✓ Fichier .env mis à jour\n";

// 3. Recherche et remplacement dans tout le projet
echo "Recherche et remplacement dans tout le projet...\n";

// Fonction pour remplacer dans les fichiers
function replaceInFiles($directory, $search, $replace, $extensions = ['php', 'blade.php', 'js', 'vue'])
{
    $iterator = new RecursiveIteratorIterator(
        new RecursiveDirectoryIterator($directory, RecursiveDirectoryIterator::SKIP_DOTS)
    );

    $count = 0;
    foreach ($iterator as $file) {
        if ($file->isFile()) {
            $extension = pathinfo($file->getFilename(), PATHINFO_EXTENSION);
            $fullName = $file->getFilename();

            // Vérifier les extensions
            $shouldProcess = false;
            foreach ($extensions as $ext) {
                if ($extension === $ext || str_ends_with($fullName, $ext)) {
                    $shouldProcess = true;
                    break;
                }
            }

            if ($shouldProcess) {
                $content = file_get_contents($file->getPathname());
                $newContent = str_replace($search, $replace, $content);

                if ($content !== $newContent) {
                    file_put_contents($file->getPathname(), $newContent);
                    $count++;
                }
            }
        }
    }

    return $count;
}

// Remplacements globaux
$replacements = [
    'Bred Fin' => $bankConfig['bank_name'],
    'Bred-Fin' => $bankConfig['bank_name_hyphen'],
    'privedyme-bank' => $bankConfig['bank_name_hyphen'],
    'contact@bred-fin.com' => $bankConfig['bank_email'],
    'contact@bred-fin.com' => $bankConfig['bank_email']
];

$totalFiles = 0;
foreach ($replacements as $search => $replace) {
    if ($search !== $replace) {
        $count = replaceInFiles(__DIR__, $search, $replace);
        if ($count > 0) {
            echo "✓ Remplacé '$search' par '$replace' dans $count fichier(s)\n";
            $totalFiles += $count;
        }
    }
}

echo "✓ Total: $totalFiles fichier(s) modifié(s)\n\n";

// 4. Exécution des commandes Laravel
echo "=== Exécution des commandes Laravel ===\n";

echo "Migration fresh et seed...\n";
exec('php artisan migrate:fresh --seed', $output, $returnCode);

if ($returnCode === 0) {
    echo "✓ Migration et seed terminés avec succès\n";
} else {
    echo "✗ Erreur lors de la migration/seed\n";
    echo implode("\n", $output) . "\n";
}

echo "\n=== Configuration terminée ===\n";
echo "La banque '{$bankConfig['bank_name']}' a été configurée avec succès !\n";
?>