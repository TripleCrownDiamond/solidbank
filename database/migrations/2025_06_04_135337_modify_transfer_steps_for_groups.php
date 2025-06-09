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
            // Remove progress_percentage column if it exists
            if (Schema::hasColumn('transfer_steps', 'progress_percentage')) {
                $table->dropColumn('progress_percentage');
            }

            // Remove account_id foreign key and column if they exist
            if (Schema::hasColumn('transfer_steps', 'account_id')) {
                $table->dropForeign(['account_id']);
                $table->dropColumn('account_id');
            }

            // Add transfer_step_group_id foreign key if it doesn't exist
            if (!Schema::hasColumn('transfer_steps', 'transfer_step_group_id')) {
                $table
                    ->foreignId('transfer_step_group_id')
                    ->constrained('transfer_step_groups')
                    ->onDelete('cascade');
            }

            // Add required columns for step management
            if (!Schema::hasColumn('transfer_steps', 'title')) {
                $table->string('title');
            }
            if (!Schema::hasColumn('transfer_steps', 'description')) {
                $table->text('description')->nullable();
            }
            if (!Schema::hasColumn('transfer_steps', 'code')) {
                $table->string('code', 50)->unique();
            }
            if (!Schema::hasColumn('transfer_steps', 'order')) {
                $table->integer('order');
            }
            if (!Schema::hasColumn('transfer_steps', 'type')) {
                $table->enum('type', ['document', 'verification', 'payment', 'confirmation']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_steps', function (Blueprint $table) {
            // Remove the columns added in up() method
            if (Schema::hasColumn('transfer_steps', 'type')) {
                $table->dropColumn('type');
            }
            if (Schema::hasColumn('transfer_steps', 'order')) {
                $table->dropColumn('order');
            }
            if (Schema::hasColumn('transfer_steps', 'code')) {
                $table->dropColumn('code');
            }
            if (Schema::hasColumn('transfer_steps', 'description')) {
                $table->dropColumn('description');
            }
            if (Schema::hasColumn('transfer_steps', 'title')) {
                $table->dropColumn('title');
            }

            // Remove transfer_step_group_id foreign key and column
            if (Schema::hasColumn('transfer_steps', 'transfer_step_group_id')) {
                $table->dropForeign(['transfer_step_group_id']);
                $table->dropColumn('transfer_step_group_id');
            }

            // Add back account_id foreign key if it doesn't exist
            if (!Schema::hasColumn('transfer_steps', 'account_id')) {
                $table
                    ->foreignId('account_id')
                    ->nullable()
                    ->constrained('accounts')
                    ->onDelete('set null');
            }
        });
    }
};
