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
    return $config && $config->bank_name ? $config->bank_name : config('app.name');
}
