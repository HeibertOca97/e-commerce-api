<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    /*
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }
*/

    public function handle($request, Closure $next, ...$guards)
    { 
        if ($request->hasHeader('token')) {
            $token = $request->headers->get('token');
            $request->headers->set('Authorization', 'Bearer ' . $token);
        }
        // Verification of the Authorization token
        $this->authenticate($request, $guards);
        $request->token_id = explode('|', $token)[0];
        return $next($request);
    }

}
