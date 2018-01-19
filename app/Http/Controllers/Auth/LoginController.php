<?php

namespace App\Http\Controllers\Auth;

use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers {
        login as performLogin;
        logout as performLogout;
        sendLoginResponse as sendLoginResponseOld;
    }

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    // public function login(Request $request)
    // {
    //     return getallheaders();
    //     return $request;
    // }


    /**
     * Handle a login request to the application. (Override)
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }
        
        $user = \App\User::whereEmail($request['email'])->first();

        if(!$user){
            return error('There is no account by that email!');
        }

        if(!$user->email_verified){ // email not verified

            if($user->invited_by){ // user got password via email, so verify their email
                $user->email_verified = 1;
                $user->save();
            }
            else{
                if(env('APP_ENV') == 'production'){
                    return error('Check the welcome email to activate your account!');
                }
            }
        }

        if ($this->attemptLogin($request)) {
            
            $this->clearLoginAttempts($request);

            // return [password_verify($user->password, '$2y$10$UAseTQSMw3Te1F58epZsAuAU0nQavvjixJQU548wjLBmFwD7yjE3.')];
            // return [password_verify($user->password, '$2y$10$DTVNzMd3Y.uTBknFSm8Pxu3Cpi4Lo0b8YnwyhQ0WAwjiVrJ69jZOm')];
            // return [password_verify($user->password, '$2y$10$ETja24x3/Tz/BYvJpPXTxeraUgUnFwSSKdxSt6pIIrLNNRyIPhjk6')];

            if (is_api()) {
                $api_token = password_hash($user->password, PASSWORD_BCRYPT);
                $previous_token = cache('user-'.$user->id);
                if($previous_token){
                    cache([$previous_token => $user->id], Carbon::now()->addDays(90));
                    cache(['user-'.$user->id => $previous_token], Carbon::now()->addDays(90));
                    $request->api_token = $previous_token;
                }
                else{
                    cache(['user-'.$user->id => $api_token], Carbon::now()->addDays(90));
                    cache([$api_token => $user->id], Carbon::now()->addDays(90));
                    $request->api_token = $api_token;
                }
                $user->notifiables = $user->notifiables;
                $user->notifications = $user->notifications;
                return success('Successfully logged in!', null, $user);
            }
            else{
                session()->flash('success', 'Successfully logged in!');
                return $this->sendLoginResponse($request);
            }
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return error('You provided a wrong password!');
    }

    /**
     * Send the response after the user was authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    protected function sendLoginResponse(Request $request)
    {
        $request->session()->regenerate();

        return $this->authenticated($request, $this->guard()->user())
                ?: redirect()->intended($this->redirectPath());
    }


    /**
     * Override
     */
    public function logout(Request $request) {
        $this->performLogout($request);
        session()->flash('success', 'Successfully logged out!');
        return redirect('login');
    }

}
