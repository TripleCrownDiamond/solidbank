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
        Schema::create('wallets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('cryptocurrency_id')->constrained()->onDelete('cascade');
            $table->string('address');
            $table->string('coin');  // ex: BTC, ETH, USDT
            $table->string('network');  // ex: Bitcoin, Ethereum, Polygon
            $table->float('balance')->default(0.0);
            $table->timestamps();

            // Ensure unique wallet per user per cryptocurrency
            $table->unique(['user_id', 'cryptocurrency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('wallets');
    }
};
