<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    private const SUPPORTED = ['en', 'sr'];

    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale', 'en');

        if (
            $request->filled('lang')
            && in_array($request->query('lang'), self::SUPPORTED, true)
        ) {
            $locale = $request->query('lang');
        } else {
            $preferred = $request->getPreferredLanguage(self::SUPPORTED);
            if ($preferred) {
                $locale = $preferred;
            }
        }

        App::setLocale($locale);

        return $next($request);
    }
}
