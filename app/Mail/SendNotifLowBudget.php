<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendNotifLowBudget extends Mailable
{
    use Queueable, SerializesModels;

    public $detail;
    public $send_from;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($detail)
    {
        $this->detail = $detail;
        $this->send_from = $detail->send_from;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from($this->send_from)
                    ->subject('Budget Reminder')
                    ->view('emails.send_notif_low_budget');
    }
}
