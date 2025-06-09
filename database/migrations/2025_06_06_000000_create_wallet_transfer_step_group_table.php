<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('wallet_transfer_step_group', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('wallet_id')
                ->constrained('wallets')
                ->onDelete('cascade');
            $table
                ->foreignId('transfer_step_group_id')
                ->constrained('transfer_step_groups')
                ->onDelete('cascade');
            $table->timestamps();

            // Ensure unique combination
            $table->unique(['wallet_id', 'transfer_step_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallet_transfer_step_group');
    }
};
