<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleCsrfErrors
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            return $next($request);
        } catch (TokenMismatchException $e) {
            // Si c'est une requête AJAX/Livewire, retourner une réponse JSON
            if ($request->expectsJson() || $request->header('X-Livewire')) {
                return response()->json([
                    'message' => 'Session expirée. Veuillez recharger la page.',
                    'csrf_error' => true
                ], 419);
            }

            // Pour les requêtes normales, rediriger vers la page de login avec un message
            $locale = app()->getLocale() ?? 'fr';
            return to_route('locale.login', ['locale' => $locale])
                ->with('error', 'Session expirée. Veuillez vous reconnecter.');
        }
    }
}