<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class ListUserEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:list-user-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'List all registered user emails.';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $users = \App\Models\User::all();

        if ($users->isEmpty()) {
            $this->info('No users found.');
            return;
        }

        $this->info('Registered User Emails:');
        foreach ($users as $user) {
            $this->comment($user->email);
        }
    }
}
