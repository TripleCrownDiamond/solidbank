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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();  // Clé primaire auto-incrémentée
            $table->string('name');  // Nom du pays
            $table->string('code', 2);  // Code ISO Alpha-2 (ex: FR)
            $table->string('dial_code');  // Indicatif téléphonique (ex: +33)
            $table->timestamps();  // created_at et updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
