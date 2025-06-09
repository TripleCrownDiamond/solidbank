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
            $table->string('logo_url')->default('img/logo_blue.svg')->after('bank_website');
            $table->string('icon_url')->default('img/icon_blue.svg')->after('logo_url');
            $table->string('favicon_url')->default('favicon.ico')->after('icon_url');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('configs', function (Blueprint $table) {
            $table->dropColumn(['logo_url', 'icon_url', 'favicon_url']);
        });
    }
};
