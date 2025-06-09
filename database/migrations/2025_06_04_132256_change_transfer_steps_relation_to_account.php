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
        Schema::table('transfer_steps', function (Blueprint $table) {
            // Drop the existing user_id foreign key and column
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');

            // Add account_id column and foreign key
            $table->unsignedBigInteger('account_id')->nullable()->after('id');
            $table->foreign('account_id')->references('id')->on('accounts')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_steps', function (Blueprint $table) {
            // Drop the account_id foreign key and column
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');

            // Restore user_id column and foreign key
            $table->unsignedBigInteger('user_id')->nullable()->after('id');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }
};
