<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    public function getAvailableLanguages()
    {
        // Liste des dossiers dans `lang/`
        $languages = File::directories(lang_path());
        
        // Extraire uniquement les codes de langue (noms des dossiers)
        return array_map(function ($path) {
            return basename($path);
        }, $languages);
    }
}