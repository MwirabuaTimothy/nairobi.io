<?php

namespace App\Http\Middleware;

use Closure;

class Admin
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
        $user = auth()->user();
        if ($user->type != 'admin') {

            $message = 'Thats an admin only feature!';

            if($request->ajax()){
                return response()->json(error($message));
            }

            return error($message);
        }
        return $next($request);
    }
}
