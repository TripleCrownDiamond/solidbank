<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class ExportUsersAndRefresh extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'users:export-and-refresh {count=10 : Number of users to generate after refresh}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Export existing users to Excel/CSV and JSON, then migrate fresh and seed with new users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('=== ' . getAppName() . ' User Export and Database Refresh ===');

        // Step 0: Clean old exports
        $this->info('\n0. Cleaning old export files...');
        $this->cleanOldExports();

        // Step 1: Export existing users
        $this->info('\n1. Exporting existing users...');
        $this->exportUsers();

        // Step 2: Migrate fresh
        $this->info('\n2. Running fresh migration...');
        $this->migrateFresh();

        // Step 3: Seed database
        $this->info('\n3. Seeding database...');
        $this->seedDatabase();

        // Step 4: Generate new users
        $count = (int) $this->argument('count');
        $this->info("\n4. Generating {$count} new users...");
        $this->generateUsers($count);

        $this->info('\n=== Process completed successfully! ===');

        return Command::SUCCESS;
    }

    /**
     * Export existing users to multiple formats
     */
    private function exportUsers()
    {
        try {
            // Get all users with relationships
            $users = User::with(['accounts', 'country'])->get();

            if ($users->isEmpty()) {
                $this->warn('No users found to export.');
                return;
            }

            $this->info("Found {$users->count()} users to export.");

            // Prepare export data
            $exportData = $users->map(function ($user) {
                $firstAccount = $user->accounts->first();

                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'first_name' => $user->first_name,
                    'last_name' => $user->last_name,
                    'email' => $user->email,
                    'phone_number' => $user->phone_number,
                    'country' => $user->country ? $user->country->name : 'Not defined',
                    'region' => $user->region,
                    'city' => $user->city,
                    'postal_code' => $user->postal_code,
                    'address' => $user->address,
                    'profession' => $user->profession,
                    'marital_status' => $user->marital_status,
                    'gender' => $user->gender,
                    'birth_date' => $user->birth_date,
                    'is_admin' => $user->is_admin ? 'Yes' : 'No',
                    'email_verified' => $user->email_verified_at ? 'Yes' : 'No',
                    'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                    'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
                    // Account information
                    'account_number' => $firstAccount ? $firstAccount->account_number : 'No account',
                    'account_type' => $firstAccount ? $firstAccount->type : 'N/A',
                    'account_currency' => $firstAccount ? $firstAccount->currency : 'N/A',
                    'account_status' => $firstAccount ? $firstAccount->status : 'N/A',
                    'account_minimum_deposit' => $firstAccount ? $firstAccount->minimum_deposit : 'N/A',
                    'suspension_reason' => $firstAccount ? $firstAccount->suspension_reason : 'N/A',
                    'suspension_instructions' => $firstAccount ? $firstAccount->suspension_instructions : 'N/A',
                ];
            });

            $timestamp = date('Y-m-d_H-i-s');

            // Export to JSON
            $this->exportToJson($exportData, $timestamp);

            // Export to CSV
            $this->exportToCsv($exportData, $timestamp);

            // Export to Excel-compatible CSV
            $this->exportToExcelCsv($exportData, $timestamp);

            // Display statistics
            $this->displayStatistics($users);
        } catch (\Exception $e) {
            $this->error('Export failed: ' . $e->getMessage());
        }
    }

    /**
     * Export data to JSON format
     */
    private function exportToJson($data, $timestamp)
    {
        $filename = "users_export_{$timestamp}.json";
        $exportsDir = storage_path('app/exports');
        $filepath = $exportsDir . DIRECTORY_SEPARATOR . $filename;

        // Ensure exports directory exists
        if (!File::exists($exportsDir)) {
            File::makeDirectory($exportsDir, 0755, true);
        }

        file_put_contents($filepath, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $this->info("✓ JSON export created: {$filepath}");
    }

    /**
     * Export data to CSV format
     */
    private function exportToCsv($data, $timestamp)
    {
        $filename = "users_export_{$timestamp}.csv";
        $exportsDir = storage_path('app/exports');
        $filepath = $exportsDir . DIRECTORY_SEPARATOR . $filename;

        $csvContent = '';

        if ($data->isNotEmpty()) {
            // Headers
            $headers = array_keys($data->first());
            $csvContent .= implode(',', $headers) . "\n";

            // Data rows
            foreach ($data as $row) {
                $csvContent .= implode(',', array_map(function ($value) {
                    return '"' . str_replace('"', '""', $value ?? '') . '"';
                }, $row)) . "\n";
            }
        }

        file_put_contents($filepath, $csvContent);
        $this->info("✓ CSV export created: {$filepath}");
    }

    /**
     * Export data to Excel-compatible CSV format (semicolon separated)
     */
    private function exportToExcelCsv($data, $timestamp)
    {
        $filename = "users_export_excel_{$timestamp}.csv";
        $exportsDir = storage_path('app/exports');
        $filepath = $exportsDir . DIRECTORY_SEPARATOR . $filename;

        $csvContent = '';

        if ($data->isNotEmpty()) {
            // Headers
            $headers = array_keys($data->first());
            $csvContent .= implode(';', $headers) . "\n";

            // Data rows
            foreach ($data as $row) {
                $csvContent .= implode(';', array_map(function ($value) {
                    return '"' . str_replace('"', '""', $value ?? '') . '"';
                }, $row)) . "\n";
            }
        }

        file_put_contents($filepath, $csvContent);
        $this->info("✓ Excel CSV export created: {$filepath}");
    }

    /**
     * Clean old export files
     */
    private function cleanOldExports()
    {
        $exportsDir = storage_path('app/exports');

        if (!File::exists($exportsDir)) {
            $this->info('✓ No exports directory found, nothing to clean');
            return;
        }

        $files = File::files($exportsDir);
        $deletedCount = 0;

        foreach ($files as $file) {
            if (File::delete($file)) {
                $deletedCount++;
            }
        }

        if ($deletedCount > 0) {
            $this->info("✓ Deleted {$deletedCount} old export file(s)");
        } else {
            $this->info('✓ No old export files to delete');
        }
    }

    /**
     * Display export statistics
     */
    private function displayStatistics($users)
    {
        $this->info('\n=== Export Statistics ===');
        $this->info('Total users: ' . $users->count());
        $this->info('Administrators: ' . $users->where('is_admin', true)->count());
        $this->info('Regular users: ' . $users->where('is_admin', false)->count());
        $this->info('With accounts: ' . $users->filter(fn($u) => $u->accounts->isNotEmpty())->count());
        $this->info('Without accounts: ' . $users->filter(fn($u) => $u->accounts->isEmpty())->count());
        $this->info('Verified emails: ' . $users->whereNotNull('email_verified_at')->count());
    }

    /**
     * Run fresh migration
     */
    private function migrateFresh()
    {
        $this->info('Running: php artisan migrate:fresh');

        $exitCode = Artisan::call('migrate:fresh', ['--force' => true]);

        if ($exitCode === 0) {
            $this->info('✓ Fresh migration completed successfully');
        } else {
            $this->error('✗ Fresh migration failed');
            throw new \Exception('Migration failed');
        }
    }

    /**
     * Seed the database
     */
    private function seedDatabase()
    {
        $this->info('Running: php artisan db:seed');

        $exitCode = Artisan::call('db:seed', ['--force' => true]);

        if ($exitCode === 0) {
            $this->info('✓ Database seeding completed successfully');
        } else {
            $this->error('✗ Database seeding failed');
            throw new \Exception('Seeding failed');
        }
    }

    /**
     * Generate new users using factory
     */
    private function generateUsers($count)
    {
        try {
            $this->info("Creating {$count} users with accounts...");

            $bar = $this->output->createProgressBar($count);
            $bar->start();

            for ($i = 0; $i < $count; $i++) {
                $user = User::factory()->create();

                // Create account for each user (as per AccountFactory default)
                \App\Models\Account::factory()->create([
                    'user_id' => $user->id
                ]);

                $bar->advance();
            }

            $bar->finish();
            $this->info("\n✓ Successfully created {$count} users with accounts");
        } catch (\Exception $e) {
            $this->error('\nUser generation failed: ' . $e->getMessage());
            throw $e;
        }
    }
}
