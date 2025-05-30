<?php

namespace App\Helpers;

use Illuminate\Support\Facades\File;

function getAvailableLocales()
{
    return collect(File::directories(base_path('lang')))
        ->map(fn($dir) => basename($dir))
        ->toArray();
}
