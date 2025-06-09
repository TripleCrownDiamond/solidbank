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
        Schema::create('cryptocurrencies', function (Blueprint $table) {
            $table->id();
            $table->string('name');  // Bitcoin, Ethereum, etc.
            $table->string('symbol', 10);  // BTC, ETH, etc.
            $table->string('network');  // Bitcoin, Ethereum, Binance Smart Chain, etc.
            $table->string('address_format');  // Format regex or description
            $table->text('address_example');  // Example address
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            // Index for better performance
            $table->index(['symbol', 'network']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cryptocurrencies');
    }
};