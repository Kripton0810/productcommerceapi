<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InventoryMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $rows;
    public $fileName;
    public function __construct($rows, $fileName)
    {
        $this->rows = $rows;
        $this->fileName = $fileName;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('mail.inventory')->attach(public_path('storage/pdf/stock_invoice/' . $this->fileName));
    }
}
