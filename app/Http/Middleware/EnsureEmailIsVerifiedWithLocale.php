<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\EnsureEmailIsVerified;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;
use Closure;

class EnsureEmailIsVerifiedWithLocale extends EnsureEmailIsVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $redirectToRoute
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse|null
     */
    public function handle($request, Closure $next, $redirectToRoute = null)
    {
        if (!$request->user() ||
            ($request->user() instanceof MustVerifyEmail &&
                !$request->user()->hasVerifiedEmail())) {
            if ($request->expectsJson()) {
                abort(403, 'Your email address is not verified.');
            }

            // Get current locale from request or app
            $locale = $request->route('locale') ?? app()->getLocale() ?? 'fr';

            // Generate URL with locale parameter
            $route = $redirectToRoute ?: 'verification.notice';
            $url = route($route, ['locale' => $locale]);

            return Redirect::guest($url);
        }

        return $next($request);
    }
}
