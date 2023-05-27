<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CreateUser extends Mailable
{
    use Queueable, SerializesModels;

    public $createUser;

    /**
     * Create a new message instance.
     *
     * @param  \App\Models\User  $createUser
     * @return void
     */
    public function __construct($createUser)
    {
        $this->createUser = $createUser;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.create-user', $this->createUser->toArray());
    }
}
