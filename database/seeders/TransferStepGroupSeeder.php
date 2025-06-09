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
        // Groupe 1: Vérification Standard (3 étapes)
        $group1 = TransferStepGroup::create([
            'name' => 'Vérification Standard',
            'description' => 'Processus de vérification standard pour les transferts de montant moyen',
            'is_active' => true,
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group1->id,
            'title' => "Vérification d'identité",
            'description' => "Vérifier l'identité du demandeur",
            'code' => 'VERIFY_ID_01',
            'order' => 1,
            'type' => 'verification',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group1->id,
            'title' => 'Validation du montant',
            'description' => 'Vérifier que le montant est conforme aux limites',
            'code' => 'VALIDATE_AMOUNT_01',
            'order' => 2,
            'type' => 'verification',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group1->id,
            'title' => 'Approbation finale',
            'description' => 'Approbation finale par un superviseur',
            'code' => 'FINAL_APPROVAL_01',
            'order' => 3,
            'type' => 'confirmation',
        ]);

        // Groupe 2: Vérification Renforcée (5 étapes)
        $group2 = TransferStepGroup::create([
            'name' => 'Vérification Renforcée',
            'description' => 'Processus de vérification renforcée pour les gros montants ou transferts sensibles',
            'is_active' => true,
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group2->id,
            'title' => "Vérification d'identité approfondie",
            'description' => "Vérification complète de l'identité avec documents supplémentaires",
            'code' => 'DEEP_VERIFY_ID_02',
            'order' => 1,
            'type' => 'document',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group2->id,
            'title' => 'Analyse de risque',
            'description' => 'Analyse approfondie des risques liés au transfert',
            'code' => 'RISK_ANALYSIS_02',
            'order' => 2,
            'type' => 'verification',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group2->id,
            'title' => 'Vérification de la source des fonds',
            'description' => "Vérifier l'origine et la légitimité des fonds",
            'code' => 'SOURCE_FUNDS_02',
            'order' => 3,
            'type' => 'verification',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group2->id,
            'title' => 'Validation par le responsable',
            'description' => 'Validation par un responsable de niveau supérieur',
            'code' => 'MANAGER_APPROVAL_02',
            'order' => 4,
            'type' => 'confirmation',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group2->id,
            'title' => 'Approbation direction',
            'description' => 'Approbation finale par la direction',
            'code' => 'DIRECTOR_APPROVAL_02',
            'order' => 5,
            'type' => 'confirmation',
        ]);

        // Groupe 3: Vérification Express (2 étapes)
        $group3 = TransferStepGroup::create([
            'name' => 'Vérification Express',
            'description' => 'Processus de vérification rapide pour les petits montants et clients de confiance',
            'is_active' => true,
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group3->id,
            'title' => 'Vérification automatique',
            'description' => 'Vérification automatisée des informations de base',
            'code' => 'AUTO_VERIFY_03',
            'order' => 1,
            'type' => 'verification',
        ]);

        TransferStep::create([
            'transfer_step_group_id' => $group3->id,
            'title' => 'Validation express',
            'description' => 'Validation rapide par un agent',
            'code' => 'EXPRESS_APPROVAL_03',
            'order' => 2,
            'type' => 'confirmation',
        ]);
    }
}
