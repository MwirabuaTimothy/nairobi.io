<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class UserController extends Controller
{
    
    /**
     * List all users for the admin
     *
     * @return void
     */
    public function index() {
        $user = request()->user;
        if($user->id == 1001){
            return success('users', null, User::all());
        }
        $collaborators = [];
        $collaborators[] = 1001; // make sure the admin is a connection!
        // add invitees
        $invitees = User::where(['invited_by' => $user->id])->get();
        foreach ($invitees as $i) {
            $collaborators[] = $i->id;
        }
        foreach ($user->lists->toArray() as $list) {
            $collaborators = (array_merge($collaborators, $list['members']));
        }
        foreach ($user->teams->toArray() as $list) {
            $collaborators = (array_merge($collaborators, $list['members']));
        }
        $collaborators = array_unique($collaborators);
        $connections = User::find($collaborators);
        return success('users', null, $connections);
    }
        
    /**
     * Return authenticated user
     *
     * @return void
     */
    public function user() {
        $user = request()->user;
        if($user){
            $user->notifiables = $user->notifiables;
            $user->notifications = $user->notifications;
            return success('user', null, $user);
        }
        else {
            return error('Expired/Invalid session!', null, 'session');
        }
    }
        
    /**
     * Update user
     *
     * @return void
     */
    public function update($id) {
        $data = request()->all();
        $user = User::findOrFail($id);
        try{
            $user->update($data);
            return success('Saved User', null, $user);
        }
        catch(Exception $e){
            return error('Failed to save user', null, $e->getMessage());
        }
    }

    /**
     * Change notifiable items
     *
     * @return void
     */
    public function changeNotifiables($id) {
        $user = User::findOrFail($id);
        $data = request()->all();
        try{
            $user->notifiables()->update($data);
            $user->notifiables = $user->notifiables;
            $user->notifications = $user->notifications;
            return success('Successfully changed setting!', null, $user);
        }
        catch(Exception $e){
            return error('Failed to change setting!', null, $e->getMessage());
        }
    }
    
    /**
     * Delete user
     *
     * @return void
     */
    public function delete($id) {
        $user = User::findOrFail($id);
        try{
            $user->delete();
            return success('Deleted User', null, $user);
        }
        catch(Exception $e){
            return error('Failed to delete user', null, $e->getMessage());
        }
    }

}
