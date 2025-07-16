<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;

class DiagnoseProduction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:diagnose-production {--fix : Tenter de corriger automatiquement les problèmes détectés}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Diagnostique les problèmes de configuration en production';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Diagnostic de la configuration de production...');
        $this->newLine();

        $issues = [];
        $warnings = [];
        $fixes = [];

        // 1. Vérification de l'environnement
        $this->info("📋 Vérification de l'environnement...");
        if (app()->environment() !== 'production') {
            $warnings[] = "L'application n'est pas en mode production (APP_ENV=" . app()->environment() . ')';
        } else {
            $this->line('✅ Mode production activé');
        }

        // 2. Vérification HTTPS
        $this->info('🔒 Vérification HTTPS...');
        $appUrl = config('app.url');
        if (!str_starts_with($appUrl, 'https://')) {
            $issues[] = 'APP_URL ne commence pas par https:// (' . $appUrl . ')';
        } else {
            $this->line('✅ APP_URL configuré pour HTTPS');
        }

        // 3. Vérification de la configuration de session
        $this->info('🍪 Vérification de la configuration de session...');

        $sessionDriver = config('session.driver');
        if ($sessionDriver !== 'database') {
            $issues[] = 'SESSION_DRIVER devrait être "database" (actuellement: ' . $sessionDriver . ')';
        } else {
            $this->line('✅ Driver de session: database');
        }

        $sessionSecure = config('session.secure');
        if (!$sessionSecure && app()->environment('production')) {
            $issues[] = 'SESSION_SECURE_COOKIE devrait être true en production';
            $fixes[] = 'Ajouter SESSION_SECURE_COOKIE=true dans .env';
        } else {
            $this->line('✅ Cookies de session sécurisés');
        }

        $sessionDomain = config('session.domain');
        if (empty($sessionDomain) && app()->environment('production')) {
            $warnings[] = "SESSION_DOMAIN n'est pas défini (recommandé: .wolf-developpe.com)";
            $fixes[] = 'Ajouter SESSION_DOMAIN=.wolf-developpe.com dans .env';
        }

        // 4. Vérification de la table sessions
        $this->info('🗄️ Vérification de la base de données...');
        try {
            if (Schema::hasTable('sessions')) {
                $sessionCount = DB::table('sessions')->count();
                $this->line('✅ Table sessions existe (' . $sessionCount . ' sessions actives)');
            } else {
                $issues[] = "Table sessions n'existe pas";
                $fixes[] = 'Exécuter: php artisan session:table && php artisan migrate';
            }
        } catch (\Exception $e) {
            $issues[] = 'Erreur de connexion à la base de données: ' . $e->getMessage();
        }

        // 5. Vérification du stockage
        $this->info('📁 Vérification du stockage...');

        $publicPath = public_path('storage');
        if (!File::exists($publicPath)) {
            $issues[] = 'Lien symbolique storage manquant';
            $fixes[] = 'Exécuter: php artisan storage:link';
        } else {
            $this->line('✅ Lien symbolique storage existe');
        }

        $storagePath = storage_path('app/public');
        if (!File::isWritable($storagePath)) {
            $issues[] = "Dossier storage/app/public n'est pas accessible en écriture";
            $fixes[] = 'Exécuter: chmod -R 755 storage && chown -R www-data:www-data storage';
        } else {
            $this->line('✅ Dossier storage accessible en écriture');
        }

        // 6. Vérification des routes critiques
        $this->info('🛣️ Vérification des routes...');
        try {
            $csrfRoute = route('csrf.token');
            $this->line('✅ Route CSRF token: ' . $csrfRoute);
        } catch (\Exception $e) {
            $issues[] = 'Route csrf.token non trouvée';
        }

        try {
            $storageRoute = route('storage.serve', ['path' => 'test']);
            $this->line('✅ Route storage fallback: ' . str_replace('/test', '/{path}', $storageRoute));
        } catch (\Exception $e) {
            $issues[] = 'Route storage.serve non trouvée';
        }

        // 7. Vérification du cache
        $this->info('⚡ Vérification du cache...');
        $configCached = File::exists(base_path('bootstrap/cache/config.php'));
        $routesCached = File::exists(base_path('bootstrap/cache/routes-v7.php'));
        $viewsCached = File::exists(storage_path('framework/views'));

        if (!$configCached) {
            $warnings[] = 'Configuration non mise en cache';
            $fixes[] = 'Exécuter: php artisan config:cache';
        } else {
            $this->line('✅ Configuration mise en cache');
        }

        if (!$routesCached) {
            $warnings[] = 'Routes non mises en cache';
            $fixes[] = 'Exécuter: php artisan route:cache';
        } else {
            $this->line('✅ Routes mises en cache');
        }

        // 8. Vérification des logs
        $this->info('📝 Vérification des logs...');
        $logPath = storage_path('logs/laravel.log');
        if (File::exists($logPath)) {
            $logSize = File::size($logPath);
            $this->line('✅ Fichier de log existe (' . $this->formatBytes($logSize) . ')');

            // Vérifier les erreurs récentes
            $logContent = File::get($logPath);
            $csrfErrors = substr_count($logContent, 'CSRF Token Mismatch');
            $storageErrors = substr_count($logContent, 'Storage file not found');

            if ($csrfErrors > 0) {
                $warnings[] = $csrfErrors . ' erreurs CSRF détectées dans les logs';
            }

            if ($storageErrors > 0) {
                $warnings[] = $storageErrors . ' erreurs de fichiers storage détectées dans les logs';
            }
        }

        // 9. Affichage des résultats
        $this->newLine();
        $this->info('📊 Résultats du diagnostic:');
        $this->newLine();

        if (empty($issues) && empty($warnings)) {
            $this->info('🎉 Aucun problème détecté ! La configuration semble correcte.');
        } else {
            if (!empty($issues)) {
                $this->error('❌ Problèmes critiques détectés:');
                foreach ($issues as $issue) {
                    $this->line('  • ' . $issue);
                }
                $this->newLine();
            }

            if (!empty($warnings)) {
                $this->warn('⚠️ Avertissements:');
                foreach ($warnings as $warning) {
                    $this->line('  • ' . $warning);
                }
                $this->newLine();
            }

            if (!empty($fixes)) {
                $this->info('🔧 Solutions recommandées:');
                foreach ($fixes as $fix) {
                    $this->line('  • ' . $fix);
                }
                $this->newLine();
            }

            // Option de correction automatique
            if ($this->option('fix')) {
                $this->info('🔧 Tentative de correction automatique...');
                $this->attemptAutoFix($issues, $fixes);
            } else {
                $this->info('💡 Utilisez --fix pour tenter une correction automatique.');
            }
        }

        return empty($issues) ? 0 : 1;
    }

    /**
     * Tente de corriger automatiquement certains problèmes
     */
    private function attemptAutoFix(array $issues, array $fixes)
    {
        foreach ($issues as $index => $issue) {
            if (str_contains($issue, 'Lien symbolique storage manquant')) {
                $this->info('Création du lien symbolique storage...');
                $this->call('storage:link');
            }

            if (str_contains($issue, "Table sessions n'existe pas")) {
                $this->info('Création de la table sessions...');
                $this->call('session:table');
                $this->call('migrate', ['--force' => true]);
            }
        }

        // Optimisation du cache
        $this->info('Optimisation du cache...');
        $this->call('config:cache');
        $this->call('route:cache');
        $this->call('view:cache');
        $this->call('optimize');

        $this->info('✅ Corrections automatiques terminées.');
    }

    /**
     * Formate la taille en octets
     */
    private function formatBytes($size, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $base = log($size, 1024);
        return round(pow(1024, $base - floor($base)), $precision) . ' ' . $units[floor($base)];
    }
}
