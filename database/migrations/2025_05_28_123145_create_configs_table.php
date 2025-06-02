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
        Schema::create('configs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // IBAN Configuration
            $table->string('iban_country_code', 2)->default('FR');
            $table->string('iban_bank_code')->default('30759');
            $table->string('iban_branch_code')->default('00001');
            $table->unsignedTinyInteger('iban_account_length')->default(11);
            $table->string('iban_prefix')->default('FR76');

            // Bank Info
            $table->string('bank_name')->default(config('app.name'));
            $table->string('bank_swift')->default('SOGEFRPP');
            $table->string('bank_country')->default('FR');
            $table->string('bank_address')->default('29 Rue du Faubourg, Paris, France');
            $table->string('bank_phone')->default('+33123456789');
            $table->string('bank_email')->default('support@bank.fr');
            $table->string('bank_website')->default('https://www.bank.fr');

            // Preferences
            $table->boolean('notification_email')->default(true);
            $table->boolean('two_factor_auth')->default(false);

            // Account Number Configuration
            $table->string('account_prefix')->default('ACC');
            $table->unsignedTinyInteger('account_length')->default(10);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('configs');
    }
};
