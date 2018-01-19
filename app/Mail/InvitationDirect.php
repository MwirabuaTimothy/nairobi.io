<?php

namespace App\Mail;

use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class InvitationDirect extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $auth;
    public $user;
    public $item;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $auth, User $user, Array $item)
    {
        $this->auth = $auth;
        $this->user = $user;
        $this->item = $item;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $auth = auth()->user();
        return $this->view('emails.invitation-direct')
                ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
                ->replyTo($this->auth->email, $this->auth->name())
                ->to($this->user->email, $this->user->name())
                ->subject('You have been invited to '.$this->item['title'].' by ' . $this->auth->first_name);
    }
}
