<?php

namespace App\Http\Middleware;

use Closure;

class Locale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $availableLocales = ['bn', 'en', 'gr', 'es', 'fr', 'jp', 'hindy', 'ar', 'bp'];
        $locale = session('APP_LOCALE');
        $locale = in_array($locale, $availableLocales) ? $locale : config('app.locale');
        app()->setLocale($locale);
        return $next($request);
    }
}
