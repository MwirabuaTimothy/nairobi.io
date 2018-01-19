<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Support\Facades\Password;
use \App\User;
use DB;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords {
        rules as oldRules;
        reset as oldReset;
    }

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Override password reset validation rules.
     *
     * @return array
     */
    protected function rules()
    {
        // return [
        //     'token' => 'required|exists:users,email_token',
        //     'email' => 'required|email|exists:users',
        //     'password' => 'required|confirmed|min:4|max:255',
        // ];
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|confirmed|min:4|max:255',
        ];
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }
    
    /**
     * Override Reseting the given user's password.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function reset(Request $request)
    {
        $this->validate($request, $this->rules(), $this->validationErrorMessages());

        // Custom validation
        $user = User::whereEmail(request('email'))->first();
        if (!$user) {
            $response = trans(Password::INVALID_USER);
            return error($response);
        }

        $query = DB::table('password_resets')->where([
            'email' => $user->email,
            'token' => request('token')
        ]);

        if (!$query->count()) {
            $response = trans(Password::INVALID_TOKEN);
            return error($response);
        }
        else {
            $query->delete();
        }
        
        // Reset the user's password.
        try{
            $user->update([
                'password' => bcrypt(request('password')),
                'email_token'=> '',
                'email_verified'=> 1
            ]);
            $response = trans(Password::PASSWORD_RESET);
            return success($response, 'login');
        } 
        catch (Exception $e) {
            // return error($e->getMessage(), 'login');
            $response = trans('passwords.error');
            return error($response);
        }
    }

}
