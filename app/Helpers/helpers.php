<?php

use App\Models\Config;
use App\Models\User;
use Illuminate\Support\Facades\File;

function getAvailableLocales()
{
    return collect(File::directories(base_path('lang')))
        ->map(fn($dir) => basename($dir))
        ->toArray();
}

function getBrandingConfig()
{
    static $config = null;

    if ($config === null) {
        $admin = User::where('is_admin', true)->first();
        $config = $admin ? Config::where('user_id', $admin->id)->first() : null;
    }

    return $config;
}

function getLogoUrl()
{
    $config = getBrandingConfig();
    return $config ? asset($config->logo_url) : asset('img/logo_blue.svg');
}

function getIconUrl()
{
    $config = getBrandingConfig();
    return $config ? asset($config->icon_url) : asset('img/icon_blue.svg');
}

function getFaviconUrl()
{
    $config = getBrandingConfig();
    return $config ? asset($config->favicon_url) : asset('favicon.ico');
}

function getAppName()
{
    $config = getBrandingConfig();
    $appName = ($config && !empty($config->bank_name)) ? $config->bank_name : config('app.name');

    if (empty($appName)) {
        return 'SolidBank';  // Fallback to a sensible default
    }

    return $appName;
}

function bank_config($key = null, $default = null)
{
    $config = getBrandingConfig();

    if ($key === null) {
        return $config;
    }

    if ($config && isset($config->{$key})) {
        return $config->{$key};
    }

    // Fallback to default values
    $defaults = [
        'bank_name' => 'DBQIC',
        'app_name' => 'DBQIC',
        'bank_swift' => 'TREUFRPP',
        'bank_country' => 'FR',
        'bank_address' => '',
        'bank_phone' => '+33123456789',
        'bank_email' => 'contact@dbqic.com',
        'bank_website' => 'https://www.dbqic.com',
    ];

    return $defaults[$key] ?? $default;
}

function isCryptoEnabled()
{
    $config = getBrandingConfig();
    return $config && isset($config->activate_crypto_features) ? (bool) $config->activate_crypto_features : true;
}
