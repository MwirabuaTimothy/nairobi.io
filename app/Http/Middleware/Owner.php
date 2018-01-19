<?php

namespace App\Http\Middleware;

use Closure;

class Owner
{
    
    /**
     * The URIs that should be excluded
     *
     * @var array
     */
    protected $except = [
        //
    ];

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
        // dd($user);

        $model = array_values($request->route()->parameters())[0];
        // dd($model);

        if ($user->type == 'admin' // is administrator
            || $model->user_id == $user->id) { // or owner

            return $next($request);
        }

        $message = 'Your account cannot do that!';

        if($request->ajax()){
            return response()->json(error($message));
        }

        return error($message);
    }
}
