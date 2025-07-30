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
        // Modifier la table account_blocks pour supprimer la colonne user_id (seulement si elle existe)
        if (Schema::hasTable('account_blocks') && Schema::hasColumn('account_blocks', 'user_id')) {
            Schema::table('account_blocks', function (Blueprint $table) {
                $table->dropForeign(['user_id']);
                $table->dropColumn('user_id');
            });
        }

        // Créer la table pivot (seulement si elle n'existe pas)
        if (!Schema::hasTable('account_account_block')) {
            Schema::create('account_account_block', function (Blueprint $table) {
                $table->id();
                $table->foreignId('account_id')->constrained()->onDelete('cascade');
                $table->foreignId('account_block_id')->constrained()->onDelete('cascade');
                $table->timestamps();
                
                // Chaque blocage ne peut être associé qu'une seule fois à un compte
                $table->unique(['account_id', 'account_block_id']);
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_account_block');
        
        // Restaurer la colonne user_id dans la table account_blocks (seulement si la table existe)
        if (Schema::hasTable('account_blocks')) {
            Schema::table('account_blocks', function (Blueprint $table) {
                $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade');
            });
        }
    }
};