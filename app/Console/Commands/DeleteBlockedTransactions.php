<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class DeleteBlockedTransactions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'transactions:delete-blocked';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete all transactions with BLOCKED status';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $deletedCount = \App\Models\Transaction::where('status', 'BLOCKED')->delete();

        if ($deletedCount > 0) {
            $this->info("Successfully deleted {$deletedCount} blocked transactions.");
        } else {
            $this->info('No blocked transactions found to delete.');
        }

        return 0;
    }
}
