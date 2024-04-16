<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendBillMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $name;
    public $rows;
    public $totalAmt;
    public $billId;
    public function __construct($name, $billId, $totalAmt, $rows)
    {
        $this->name = $name;
        $this->billId = $billId;
        $this->totalAmt = $totalAmt;
        $this->rows = $rows;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.bill')->subject('Thanks for purchase with Om Prakash Store');
    }
}
