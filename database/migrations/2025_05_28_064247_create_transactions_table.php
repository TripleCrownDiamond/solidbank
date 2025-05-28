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
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            $table->float('amount');

            $table->enum('type', ['DEPOSIT', 'WITHDRAWAL', 'TRANSFER_BANK', 'TRANSFER_CRYPTO', 'TRANSFER_EXTERNAL']);

            $table->enum('status', ['PENDING', 'COMPLETED', 'FAILED']);

            $table->string('description')->nullable();
            $table->string('reference')->unique();

            $table->foreignId('from_account_id')->nullable()->constrained('accounts')->nullOnDelete();
            $table->foreignId('to_account_id')->nullable()->constrained('accounts')->nullOnDelete();

            // Infos spécifiques aux transferts externes
            $table->json('external_crypto_info')->nullable();
            $table->json('external_bank_info')->nullable();

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
