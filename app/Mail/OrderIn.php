<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderIn extends Mailable
{
    use Queueable, SerializesModels;

    public $orderIn;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderIn)
    {
        $this->$orderIn = $orderIn;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.order-in', $this->orderIn);
    }
}
