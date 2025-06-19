<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\TransferOtpMail;
use App\Models\User;

class TestEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test-email';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test email sending';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $user = User::first();
        if ($user) {
            $otp = random_int(100000, 999999);
            
            $this->info('Sending test email to: ' . $user->email);
            
            try {
                Mail::to($user->email)->send(new TransferOtpMail($user, $otp));
                $this->info('Email sent successfully!');
            } catch (\Exception $e) {
                $this->error('Failed to send email: ' . $e->getMessage());
            }
        } else {
            $this->error('No user found in database');
        }
    }
}
