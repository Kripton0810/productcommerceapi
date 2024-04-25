<?php

namespace App\Console\Commands;

use App\Mail\InventoryMail;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class SendInventoryUpdate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'inventory:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check the inventory and send all the less items from the inventory';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $data = Product::all();
        $rows = Product::where('stock', '<=', 10)
            ->orderBy('id')
            ->get(['name', 'sku', 'stock']);


        $pdf = PDF::setOptions(['isRemoteEnabled' => true])->loadView('pdf.stocks', compact('data'));
        $pdf->setPaper('A4');
        $pdf->download();
        $currentTime = uniqid(rand()) . time();
        $fileNamePass = $currentTime . ".pdf";
        $filename = "public/pdf/stock_invoice/" . $currentTime . ".pdf";
        Storage::put($filename, $pdf->output());
        $invoice_url = url(Storage::url($filename));
        $mail = Mail::to("subhankar0810@gmail.com")->send(new InventoryMail($rows, $fileNamePass));
        $this->info('Mail send some product are less then 10');


        return 0;
    }
}
///* * * * * php /path-to-your-project/artisan inventory:update > /dev/null 2>&1 *
