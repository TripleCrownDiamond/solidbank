<?php

namespace App\Helpers;

use App\Models\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;

class BankConfigHelper
{
    /**
     * Récupère la configuration bancaire avec mise en cache
     */
    public static function getConfig()
    {
        try {
            // Vérifier si la base de données est accessible
            if (!\Schema::hasTable('configs')) {
                return null;
            }
            
            return Cache::remember('bank_config', 3600, function () {
                return Config::first();
            });
        } catch (\Exception $e) {
            return null;
        }
    }

    /**
     * Vide le cache de configuration
     */
    public static function clearCache()
    {
        Cache::forget('bank_config');
    }

    /**
     * Récupère une valeur spécifique de la configuration
     */
    public static function get($key, $default = null)
    {
        $config = self::getConfig();
        return $config ? ($config->{$key} ?? $default) : $default;
    }
}
