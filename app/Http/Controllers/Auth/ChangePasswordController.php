<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class ChangePasswordController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('api_token');
    }

    /**
     * Get a validator for an incoming password-change request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'old_password' => 'required',
            'password' => 'required|confirmed|min:4',
        ]);
    }

    /**
     * Get the user credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return [
            'email' => request()->user->email, 
            'password' => $request->old_password
        ];
    }


    /**
     * Change the given user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function change(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) 
        {
            return validater($validator);
        }

        if(auth()->attempt($this->credentials($request))){
            request()->user()->update(['password' => bcrypt($request->password)]);
            return success('Successfully changed your password!');
        }
        return error('You provided the wrong current password!');

    }

}
