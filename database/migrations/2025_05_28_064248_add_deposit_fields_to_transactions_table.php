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
        Schema::table('transactions', function (Blueprint $table) {
            // Add user_id to track which user initiated the transaction
            $table->foreignId('user_id')->nullable()->after('id')->constrained('users')->nullOnDelete();
            
            // Add account_id for account-based transactions (separate from from_account_id/to_account_id)
            $table->foreignId('account_id')->nullable()->after('user_id')->constrained('accounts')->nullOnDelete();
            
            // Add wallet_id for wallet-based transactions
            $table->foreignId('wallet_id')->nullable()->after('account_id')->constrained('wallets')->nullOnDelete();
            
            // Add currency field to track the currency of the transaction
            $table->string('currency', 10)->nullable()->after('amount');
            
            // Make reference nullable since it might be generated after creation
            $table->string('reference')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropColumn('user_id');
            
            $table->dropForeign(['account_id']);
            $table->dropColumn('account_id');
            
            $table->dropForeign(['wallet_id']);
            $table->dropColumn('wallet_id');
            
            $table->dropColumn('currency');
            
            // Restore reference as required
            $table->string('reference')->unique()->change();
        });
    }
};