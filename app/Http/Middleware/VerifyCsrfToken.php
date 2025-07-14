<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;
use Illuminate\Session\TokenMismatchException;
use Closure;
use Illuminate\Http\Request;

class VerifyCsrfToken extends Middleware
{
    /**
     * The URIs that should be excluded from CSRF verification.
     *
     * @var array<int, string>
     */
    protected $except = [
        // Routes exclues de la vérification CSRF
        'storage/*', // Pour les routes de stockage de fichiers
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     * @throws \Illuminate\Session\TokenMismatchException
     */
    public function handle($request, Closure $next)
    {
        try {
            return parent::handle($request, $next);
        } catch (TokenMismatchException $e) {
            // Gestion spéciale des erreurs CSRF en production
            if (app()->environment('production')) {
                // Log l'erreur pour le débogage
                \Log::warning('CSRF Token Mismatch', [
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'user_agent' => $request->userAgent(),
                    'ip' => $request->ip(),
                    'session_id' => $request->session()->getId(),
                ]);

                // Pour les requêtes AJAX/Livewire, retourner une réponse JSON
                if ($request->expectsJson() || $request->header('X-Livewire')) {
                    return response()->json([
                        'message' => 'Session expirée. Veuillez actualiser la page.',
                        'reload' => true
                    ], 419);
                }

                // Pour les requêtes normales, rediriger avec un message
                return redirect()->back()
                    ->withInput($request->except('_token', 'password'))
                    ->withErrors(['csrf' => 'Votre session a expiré. Veuillez réessayer.']);
            }

            // En développement, laisser l'exception se propager
            throw $e;
        }
    }

    /**
     * Determine if the session and input CSRF tokens match.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return bool
     */
    protected function tokensMatch($request)
    {
        $token = $this->getTokenFromRequest($request);

        return is_string($request->session()->token()) &&
               is_string($token) &&
               hash_equals($request->session()->token(), $token);
    }
}