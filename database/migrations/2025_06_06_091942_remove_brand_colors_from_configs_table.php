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
        Schema::table('configs', function (Blueprint $table) {
            // Supprimer toutes les colonnes de couleurs de marque
            $table->dropColumn([
                'brand_color',
                'brand_primary_hover',
                'brand_primary_light',
                'brand_primary_dark',
                'brand_secondary',
                'brand_accent',
                'brand_success',
                'brand_warning',
                'brand_error'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            // Recréer les colonnes de couleurs de marque
            $table->string('brand_color')->default('#2563eb')->after('favicon_url');
            $table->string('brand_primary_hover')->default('#1d4ed8')->after('brand_color');
            $table->string('brand_primary_light')->default('#3b82f6')->after('brand_primary_hover');
            $table->string('brand_primary_dark')->default('#1e40af')->after('brand_primary_light');
            $table->string('brand_secondary')->default('#64748b')->after('brand_primary_dark');
            $table->string('brand_accent')->default('#10b981')->after('brand_secondary');
            $table->string('brand_success')->default('#059669')->after('brand_accent');
            $table->string('brand_warning')->default('#d97706')->after('brand_success');
            $table->string('brand_error')->default('#dc2626')->after('brand_warning');
        });
    }
};
