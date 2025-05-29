<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;

class LanguageController extends Controller
{
    /**
     * Get all available languages from the lang directory
     *
     * @return array
     */
    public function getAvailableLanguages()
    {
        $langPath = lang_path();
        if (!File::exists($langPath)) {
            return ['en']; // Retourne l'anglais par défaut si le dossier n'existe pas
        }

        $directories = File::directories($langPath);
        return array_map('basename', $directories);
    }
}