<?php
namespace Botble\Base\Http\Middleware;

use Closure;

class HttpsProtocol
{

    /**
     * @param $request
     * @param Closure $next
     * @return \Illuminate\Http\RedirectResponse
     * @author Sang Nguyen
     */
    public function handle($request, Closure $next)
    {
        if (!$request->secure() && setting('enable_https')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
