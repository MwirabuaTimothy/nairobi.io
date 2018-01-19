<?php

namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Mail\Invitation;
use App\Mail\InvitationDirect;
use App\Mail\EmailVerification;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    
    protected $redirectTo = '/login';

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
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255', // |unique:users - check if invited, send different message
            'password' => 'required|string|min:4|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        // create the user
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'email_token' => md5(microtime()),
            'invited_by' => isset($data['invited_by']) ? $data['invited_by'] : null,
        ]);
        // create notifiable trait
        $user->notifiables()->create([
            'user_id' => $user->id,
            'team_add' => true,
            'team_remove' => true,
            'team_admin' => false,
            'list_add' => true,
            'list_remove' => true,
            'list_admin' => false,
            'clip_added' => true,
            'list_members' => false,
            'comment_new' => true,
            'comment_reply' => true,
            'request_response' => true,
        ]);

        // create first team
        $team = \App\Team::create([
            'user_id' => $user->id,
            'title' => 'Example Team',
            'intro' => 'Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Lorem ipsum dolor sit amet',
        ]);
        $team->membership()->attach(1001); // add member "PressDesk Support"
        $team->membership()->attach($user->id); // make the user a member
        $team->administration()->attach($user->id); // make user an admin
        
        // create first list
        $list = \App\Listt::create([
            'user_id' => $user->id,
            'title' => 'Example List',
            'intro' => 'Lorem ipsum dolor sit amet.Lorem ipsum dolor sit amet, consectetuer adipiscing elit, consectetuer adipiscing elit, sed diam nonummy nibh euismod tincidunt ut laoreet dolore magna aliquam erat volutpat.Lorem ipsum dolor sit amet',
        ]);
        $list->membership()->attach(1001); // add member "PressDesk Support"
        $list->membership()->attach($user->id); // make the user a member
        $list->administration()->attach($user->id); // make user an admin

        return $user;
    }

    /**
    *  Override RegistersUsers trait to send EmailVefification
    */
    public function register(Request $request)
    {
        // Laravel validation
        $validator = $this->validator($request->all());
        if ($validator->fails()) 
        {
            return validater($validator);
        }
        $user = User::whereEmail($request['email'])->first();
        if($user){
            if($user->invited_by){
                $inviter = User::find($user->invited_by);
                return warning('You were invited by '. $inviter->name(). ' on '. $user->created_at->toDateString() .'. and emailed a link!');
            }
            return error('That email has already been registered!');
        }
        // Use a database transaction
        DB::beginTransaction();
        try
        {
            $user = $this->create($request->all());
            
            // Send an email with the random token generated in the create method above
            if(env('APP_ENV') == 'production'){ // only on production
                $email = new EmailVerification(new User(['email_token' => $user->email_token, 'name' => $user->name()]));
                Mail::to($user->email)->send($email);
            }
            
            DB::commit();

            // announce the event
            event(new Registered($user));

            return success('Successfull! Check '.$user->email.' for verification!', 'login');
        }
        catch(Exception $e)
        {
            DB::rollback(); 
            return error('Oops! Please retry! '.$e->getMessage());
        }

    }
    // Get the user who has the same token and change his/her status to verified i.e. 1
    public function verifyEmail($token)
    {
        $user = User::where('email_token', $token)->first();
        if(!$user){
            return error('Sorry, your link is invalid or expired!', 'password/reset');
        }

        $user->verifyEmail();
        // auth()->login($user);
        return success('Successfully verified email! Welcome!', 'login', $user);
    }
    
    // Invite an account
    public function invitation()
    {
        $data = request()->all();
        $data['invited_by'] = request()->user->id;
        $data['password'] = 'invitation';
        $data['password_confirmation'] = 'invitation';
        
        // Laravel validation
        $validator = $this->validator($data);
        if ($validator->fails()) 
        {
            return validater($validator);
        }

        if(!request('team_id') && !request('list_id')){
            return error('Please select a team or list!');
        }

        $user = User::whereEmail(request('email'))->first();

        if(!$user){
            // Use a database transaction
            \DB::beginTransaction();
            try
            {
                $user = $this->create($data);                

                if(request('team_id')){
                    $item = \App\Team::findOrFail(request('team_id'));
                    if($item->membership->contains($user->id)){
                        return success($user->first_name. ' is already a member', null, $user);
                    }
                    else {
                        $item->membership()->attach($user);
                        $item = $item->toArray();
                        $item['type'] = 'team';
                    }

                }
                if(request('list_id')){
                    $item = \App\Listt::findOrFail(request('list_id'));
                    if($item->membership->contains($user->id)){
                        return success($user->first_name. ' is already a member', null, $user);
                    }
                    else {
                        $item->membership()->attach($user);
                        $item = $item->toArray();
                        $item['type'] = 'list';
                    }
                }
                
                // Send the invitation email
                if(env('APP_ENV') == 'production'){ // only on production
                    Mail::send(new Invitation(request()->user, $user, $item));
                }
                
                \DB::commit();

                // announce the event
                event(new Registered($user));
            }
            catch(Exception $e)
            {
                \DB::rollback(); 
                return error('Failed to save user! '.$e->getMessage());
            }
        }
        else {

            if(request('team_id')){
                $item = \App\Team::findOrFail(request('team_id'));
                if($item->membership->contains($user->id)){
                    return success($user->first_name. ' is already a member', null, $user);
                }
                else {
                    $item->membership()->attach($user);
                    $item = $item->toArray();
                    $item['type'] = 'team';
                }

            }
            if(request('list_id')){
                $item = \App\Listt::findOrFail(request('list_id'));
                if($item->membership->contains($user->id)){
                    return success($user->first_name. ' is already a member', null, $user);
                }
                else {
                    $item->membership()->attach($user);
                    $item = $item->toArray();
                    $item['type'] = 'list';
                }
            }
                
            // Send the invitation email
            if(env('APP_ENV') == 'production'){ // only on production
                Mail::send(new InvitationDirect(request()->user, $user, $item));
            }
            
        }
        return success('Successfull! '.$user->email.' has been invited!', null, $user);
    }

    // Verify an invited account
    public function setPassword()
    {
        $user = User::where('email', request('email'))->first();

        if(!$user){
            return error('Sorry, your link is invalid or expired!', 'password/reset');
        }
        else {
            $user->password = bcrypt(request('password'));
            $user->save();
        }
        // set an api token
        $api_token = password_hash($user->password, PASSWORD_BCRYPT);
        cache(['user-'.$user->id => $api_token], Carbon::now()->addDays(90));
        cache([$api_token => $user->id], Carbon::now()->addDays(90));
        request()->api_token = $api_token;

        $user->verifyEmail();
        
        $user->notifiables = $user->notifiables;
        $user->notifications = $user->notifications;
        return success('Successfully set password!', 'login', $user);
    }
}