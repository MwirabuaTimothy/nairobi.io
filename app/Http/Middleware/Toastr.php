<?php

namespace App\Http\Middleware;

use Closure;

class Toastr
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
        
        $types = ['success', 'warning', 'info', 'danger', 'message'];

        // for session()->flash($type, 'Message...')
        foreach ($types as $type):
            if (session($type)):
                $msg = session($type);
                
                if(is_array(json_decode($msg,true))):
                    $msg = implode('', $msg->all(':message<br/>'));
                endif;
                if ($type == 'danger') $type = 'error';
                session()->put('toastr.level', $type);
                session()->put('toastr.message', $msg);
            endif;
        endforeach;

        // for form validation errors
        if ($errors = $request->session()->get('errors')):
            $msg = '';
            foreach ($errors->all() as $error):
                $msg .= $error.'<br/>';
            endforeach;
            
            session()->put('toastr.level', 'error');
            session()->put('toastr.message', $msg);
        endif;

        return $next($request);
    }
}
