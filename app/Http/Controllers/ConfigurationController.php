<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ConfigurationController extends Controller
{
    /**
     * Show the configuration form.
     */
    public function show(Request $request)
    {
        // Vérifier que l'utilisateur est un administrateur
        if (!Auth::user() || !Auth::user()->is_admin) {
            abort(403, 'Accès non autorisé. Seuls les administrateurs peuvent accéder à cette page.');
        }

        return view('profile.show-configuration', [
            'request' => $request,
        ]);
    }
}