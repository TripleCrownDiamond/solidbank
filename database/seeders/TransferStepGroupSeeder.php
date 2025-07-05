<?php

namespace Database\Seeders;

use App\Models\TransferStep;
use App\Models\TransferStepGroup;
use Illuminate\Database\Seeder;

class TransferStepGroupSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Groupe unique: Vérification Standard (3 étapes)
        $group = TransferStepGroup::create([
            'name' => 'Vérification Standard',
            'description' => 'Processus de vérification standard pour tous les transferts',
            'is_active' => true,
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group->id,
            'title' => "Vérification d'identité",
            'description' => "Vérifier l'identité du demandeur",
            'code' => 'VERIFY_ID_01',
            'order' => 1,
            'type' => 'verification',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group->id,
            'title' => 'Validation du montant',
            'description' => 'Vérifier que le montant est conforme aux limites',
            'code' => 'VALIDATE_AMOUNT_01',
            'order' => 2,
            'type' => 'verification',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group->id,
            'title' => 'Approbation finale',
            'description' => 'Approbation finale par un superviseur',
            'code' => 'FINAL_APPROVAL_01',
            'order' => 3,
            'type' => 'confirmation',
        ]);
    }
}
