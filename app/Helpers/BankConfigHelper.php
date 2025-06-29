<?php

namespace App\Helpers;

use App\Models\Config;
use Illuminate\Support\Facades\Cache;

class BankConfigHelper
{
    /**
     * Récupère la configuration bancaire avec mise en cache
     */
    public static function getConfig()
    {
        return Cache::remember('bank_config', 3600, function () {
            return Config::first() ?? self::getDefaultConfig();
        });
    }

    /**
     * Configuration par défaut si aucune n'existe en base
     */
    private static function getDefaultConfig()
    {
        return (object) [
            'bank_name' => 'Bred Fin',
            'bank_swift' => 'SOLDFR21',
            'bank_country' => 'France',
            'bank_address' => '123 Avenue des Champs-Élysées, 75008 Paris, France',
            'bank_phone' => '+33 1 23 45 67 89',
            'bank_email' => 'contact@Bred Fin.fr',
            'bank_website' => 'https://Bred Fin.fr',
            'logo_url' => '/images/logo.png',
            'icon_url' => '/images/icon.png',
            'favicon_url' => '/images/favicon.ico',
            'notification_email' => 'notifications@Bred Fin.fr',
            'brand_color' => '#3B82F6',
            'brand_primary_hover' => '#2563EB',
            'brand_primary_light' => '#DBEAFE',
            'brand_primary_dark' => '#1E40AF',
            'brand_secondary' => '#8B5CF6',
            'brand_accent' => '#10B981',
            'brand_success' => '#059669',
            'brand_warning' => '#D97706',
            'brand_error' => '#DC2626',
        ];
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
        return $config->{$key} ?? $default;
    }
}
