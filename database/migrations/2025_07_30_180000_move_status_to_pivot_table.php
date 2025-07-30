<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Ajouter le champ status à la table pivot account_account_block
        Schema::table('account_account_block', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active')->after('account_block_id');
        });

        // Migrer les données existantes du status de account_blocks vers account_account_block
        DB::statement('
            UPDATE account_account_block 
            SET status = (
                SELECT status 
                FROM account_blocks 
                WHERE account_blocks.id = account_account_block.account_block_id
            )
        ');

        // Supprimer le champ status de la table account_blocks
        Schema::table('account_blocks', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Restaurer le champ status dans account_blocks
        Schema::table('account_blocks', function (Blueprint $table) {
            $table->enum('status', ['active', 'inactive'])->default('active');
        });

        // Migrer les données de retour (prendre le premier status trouvé pour chaque block)
        DB::statement('
            UPDATE account_blocks 
            SET status = (
                SELECT status 
                FROM account_account_block 
                WHERE account_account_block.account_block_id = account_blocks.id
                LIMIT 1
            )
        ');

        // Supprimer le champ status de la table pivot
        Schema::table('account_account_block', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};
