<?php

namespace App\Http\Middleware;

use Closure;

class AddAuthVariable
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
        $auth = auth()->user();
        
        // for controllers
        $request->auth = $auth; 
        
        // for views
        view()->share('auth', $auth);

        return $next($request);
    }
}
