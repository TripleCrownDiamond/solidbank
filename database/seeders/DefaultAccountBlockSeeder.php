<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AccountBlock;

class DefaultAccountBlockSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Vérifier si le blocage par défaut existe déjà
        $existingBlock = AccountBlock::where('reason', 'Vérification d\'identité')
            ->first();

        if (!$existingBlock) {
            // Lire la configuration de la banque
            $bankConfig = json_decode(file_get_contents(base_path('bank-config.json')), true);
            $bankName = $bankConfig['bank_name'] ?? 'Nouvelle Banque';

            // Créer le blocage par défaut
            AccountBlock::create([
                'reason' => 'Vérification d\'identité',
                'description' => 'Votre compte a été temporairement bloqué en attente de vérification d\'identité. Il s\'agit d\'une mesure de sécurité standard pour protéger votre compte et respecter la réglementation bancaire.',
                'instructions' => "Pour lever ce blocage et vérifier votre identité, vous devez effectuer un virement bancaire depuis un compte bancaire existant qui porte les mêmes nom et prénom que votre compte chez {$bankName}. Veuillez transférer le montant exact spécifié ci-dessous vers notre compte bancaire. Une fois que nous aurons reçu et vérifié le virement, votre compte sera débloqué dans les 24 à 48 heures.",
                'amount_to_pay' => 1.00, // 1 euro pour la vérification
                'show_rib' => true,
                'request_id_document' => false,
            ]);

            $this->command->info('Blocage par défaut créé avec succès.');
        } else {
            $this->command->info('Le blocage par défaut existe déjà.');
        }
    }
}