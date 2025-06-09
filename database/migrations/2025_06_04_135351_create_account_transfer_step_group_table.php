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
        Schema::create('account_transfer_step_group', function (Blueprint $table) {
            $table->id();
            $table
                ->foreignId('account_id')
                ->constrained('accounts')
                ->onDelete('cascade');
            $table
                ->foreignId('transfer_step_group_id')
                ->constrained('transfer_step_groups')
                ->onDelete('cascade');
            $table->timestamps();

            // Ensure unique combination
            $table->unique(['account_id', 'transfer_step_group_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('account_transfer_step_group');
    }
};
