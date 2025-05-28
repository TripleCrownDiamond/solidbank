<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('account_number')->unique();
            $table->enum('type', ['CHECKING', 'SAVINGS'])->default('CHECKING');
            $table->enum('status', ['ACTIVE', 'INACTIVE', 'SUSPENDED'])->default('INACTIVE');
            $table->string('currency')->default('EUR');
            $table->float('balance')->default(0);
            $table->float('minimum_deposit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('accounts');
    }
};
