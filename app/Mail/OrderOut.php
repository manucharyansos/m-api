<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OrderOut extends Mailable
{
    use Queueable, SerializesModels;

    public $orderOut;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($orderOut)
    {
        $this->orderOut = $$orderOut;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.order-out', $this->orderOut);
    }
}
