<?php

namespace Botble\Base\Http\Middleware;

use Assets;
use Botble\ACL\Models\UserMeta;
use Closure;
use Sentinel;

class Locale
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     * @author Sang Nguyen
     */
    public function handle($request, Closure $next)
    {
        if ($request->is(config('cms.admin_dir') . '/*')) {
            $locales = Assets::getAdminLocales();

            if (Sentinel::check()) {
                $locale = UserMeta::getMeta('admin-locale', config('app.locale'));

                if (array_key_exists($locale, $locales)) {
                    if ($locale != false) {
                        app()->setLocale($locale);
                    }
                }
            } elseif (session()->has('admin-locale')) {
                if (array_key_exists(session('admin-locale'), $locales)) {
                    app()->setLocale(session('admin-locale'));
                }
            }
        }

        return $next($request);
    }
}
