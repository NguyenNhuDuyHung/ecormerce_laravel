<?php
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request
     * @param \Closure(\Iluminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next;
     */

    public function handle(Request $request, Closure $next): Response
    {
        $locale = session('app_locale', config('app.locale'));
        \App::setLocale($locale);

        return $next($request);
    }
}