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
        try {
            return Cache::remember('bank_config', 3600, function () {
                return Config::first() ?? self::getDefaultConfig();
            });
        } catch (\Exception $e) {
            // Si les facades ne sont pas encore disponibles, retourner la config par défaut
            return self::getDefaultConfig();
        }
    }

    /**
     * Configuration par défaut si aucune n'existe en base
     */
    private static function getDefaultConfig()
    {
        return (object) [
            'bank_name' => 'Wolf Developpe',
            'bank_swift' => 'WOLFFRPP',
            'bank_country' => 'FR',
            'bank_address' => '29 Rue du Faubourg, Paris, France',
            'bank_phone' => '+33123456789',
            'bank_email' => 'contact@wolf-developpe.com',
            'bank_website' => 'https://www.wolf-developpe.com',
            'logo_url' => 'img/logo_blue.svg',
            'icon_url' => 'img/icon_blue.svg',
            'favicon_url' => 'favicon.ico',
            'notification_email' => 'contact@wolf-developpe.com',
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
