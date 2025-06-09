<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Ajouter les champs pour les étapes de transfert
            $table->foreignId('blocked_at_transfer_step_id')
                  ->nullable()
                  ->constrained('transfer_steps')
                  ->nullOnDelete()
                  ->after('external_bank_info');
                  
            $table->foreignId('blocked_at_transfer_step_group_id')
                  ->nullable()
                  ->constrained('transfer_step_groups')
                  ->nullOnDelete()
                  ->after('blocked_at_transfer_step_id');
                  
            // Champ pour indiquer si la transaction est bloquée
            $table->boolean('is_blocked')->default(false)->after('blocked_at_transfer_step_group_id');
            
            // Raison du blocage
            $table->text('blocked_reason')->nullable()->after('is_blocked');
            
            // Date de blocage
            $table->timestamp('blocked_at')->nullable()->after('blocked_reason');
            
            // Champ pour l'admin qui a confirmé/annulé
            $table->foreignId('processed_by_admin_id')
                  ->nullable()
                  ->constrained('users')
                  ->nullOnDelete()
                  ->after('blocked_at');
                  
            // Date de traitement par l'admin
            $table->timestamp('processed_at')->nullable()->after('processed_by_admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['blocked_at_transfer_step_id']);
            $table->dropForeign(['blocked_at_transfer_step_group_id']);
            $table->dropForeign(['processed_by_admin_id']);
            
            $table->dropColumn([
                'blocked_at_transfer_step_id',
                'blocked_at_transfer_step_group_id',
                'is_blocked',
                'blocked_reason',
                'blocked_at',
                'processed_by_admin_id',
                'processed_at'
            ]);
        });
    }
};