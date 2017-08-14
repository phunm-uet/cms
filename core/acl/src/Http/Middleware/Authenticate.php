<?php

namespace Botble\ACL\Http\Middleware;

use Botble\MenuLeftHand\Models\MenuLeftHand;
use Closure;
use Sentinel;

class Authenticate
{

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  Closure $next
     * @return mixed
     * @author Sang Nguyen
     */
    public function handle($request, Closure $next)
    {
        if (!Sentinel::check()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest(route('access.login'));
            }
        }

        $menuLeftHand = session()->get('menu_left_hand');
        if (!isset($menuLeftHand) || $menuLeftHand === '') {
            MenuLeftHand::buildMenu();
        }

        $route = $request->route()->getAction();
        if (array_key_exists('permission', $route)) {
            if ($route['permission'] && !Sentinel::getUser()->hasPermission($route['permission'])) {
                abort(401);
            }
        } elseif (array_key_exists('as', $route)) {
            if (!Sentinel::getUser()->hasPermission($route['as'])) {
                abort(401);
            }
        }

        return $next($request);
    }
}
