<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactUs extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.simple')
            // ->from($data['email'], $data['name']) // user might provide fake email signature and it will bounce
            ->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            ->replyTo($this->data['reply_email'], $this->data['reply_name'])
            ->to(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'))
            // ->cc(env('MAIL_CC_ADDRESS'), env('MAIL_CC_NAME'))
            ->subject('New Inquiry | '.env('APP_NAME').' Support');
    }
}
