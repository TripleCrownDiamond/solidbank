<?php

/**
 * Script d'export des utilisateurs SolidBank
 * Exporte tous les utilisateurs avec leurs comptes associés
 */
require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Account;
use App\Models\User;

echo "=== Export des utilisateurs SolidBank ===\n";
echo "Début de l'export...\n\n";

// Récupérer tous les utilisateurs avec leurs comptes
$users = User::with(['account', 'country'])->get();

echo "Nombre d'utilisateurs trouvés: " . $users->count() . "\n";

// Préparer les données pour l'export
$exportData = $users->map(function ($user) {
    return [
        'id' => $user->id,
        'nom' => $user->name,
        'prenom' => $user->first_name,
        'nom_famille' => $user->last_name,
        'email' => $user->email,
        'telephone' => $user->phone_number,
        'pays' => $user->country ? $user->country->name : 'Non défini',
        'ville' => $user->city,
        'adresse' => $user->address,
        'profession' => $user->profession,
        'statut_marital' => $user->marital_status,
        'genre' => $user->gender,
        'date_naissance' => $user->birth_date,
        'est_admin' => $user->is_admin ? 'Oui' : 'Non',
        'statut' => $user->status,
        'email_verifie' => $user->email_verified_at ? 'Oui' : 'Non',
        'date_creation' => $user->created_at->format('Y-m-d H:i:s'),
        // Informations du compte
        'numero_compte' => $user->account ? $user->account->account_number : 'Aucun compte',
        'solde' => $user->account ? $user->account->balance : 'N/A',
        'devise' => $user->account ? $user->account->currency : 'N/A',
        'type_compte' => $user->account ? $user->account->type : 'N/A',
        'statut_compte' => $user->account ? $user->account->status : 'N/A',
    ];
});

// Export en JSON
$jsonFile = 'users_export_' . date('Y-m-d_H-i-s') . '.json';
file_put_contents($jsonFile, json_encode($exportData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
echo "✓ Export JSON créé: {$jsonFile}\n";

// Export en CSV
$csvFile = 'users_export_' . date('Y-m-d_H-i-s') . '.csv';
$csvContent = '';

// En-têtes CSV
if ($exportData->isNotEmpty()) {
    $headers = array_keys($exportData->first());
    $csvContent .= implode(';', $headers) . "\n";

    // Données
    foreach ($exportData as $row) {
        $csvContent .= implode(';', array_map(function ($value) {
            return '"' . str_replace('"', '""', $value) . '"';
        }, $row)) . "\n";
    }
}

file_put_contents($csvFile, $csvContent);
echo "✓ Export CSV créé: {$csvFile}\n";

// Statistiques
echo "\n=== Statistiques ===\n";
echo 'Total utilisateurs: ' . $users->count() . "\n";
echo 'Administrateurs: ' . $users->where('is_admin', true)->count() . "\n";
echo 'Utilisateurs normaux: ' . $users->where('is_admin', false)->count() . "\n";
echo 'Avec compte: ' . $users->whereNotNull('account')->count() . "\n";
echo 'Sans compte: ' . $users->whereNull('account')->count() . "\n";
echo 'Emails vérifiés: ' . $users->whereNotNull('email_verified_at')->count() . "\n";

echo "\n=== Export terminé avec succès! ===\n";
