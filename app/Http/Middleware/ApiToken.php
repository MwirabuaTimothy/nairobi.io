<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\User;

class ApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        // return $request->header();
        $api_token = request()->header('api_token');
        if (!$api_token) {
            return response(error('Missing Token! Kindly log in.'));
        }
        else {
            $user_id = (int) cache($api_token);
            if (!$user_id) {
                return response(error('Expired Token! Kindly log in.'));
            }
            else {
                $request->api_token = $api_token;
                $user = User::find($user_id);
                // return response(error($user));
                $request->user = $user;
                // return response(error($request->user));
                // auth()->login($user);
                return $next($request);
            }
        }
    }
}
