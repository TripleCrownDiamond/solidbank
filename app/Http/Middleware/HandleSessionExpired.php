<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Session\TokenMismatchException;
use Symfony\Component\HttpFoundation\Response;

class HandleSessionExpired
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
            // Handle session expired silently
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Session expired'], 419);
            }
            
            // For web requests, redirect to login without showing error
            return redirect()->route('login')->with('silent_session_expired', true);
        }
    }
} 