<?php

namespace App\Console\Commands;

use App\Mail\InventoryMail;
use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

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

        $rows = Product::where('stock', '<=', 10)
            ->orderBy('id')
            ->get(['name', 'sku', 'stock']);

        if ($rows->isEmpty()) {
            $this->info('All product are more then enough');
        } else {
            $mail = Mail::to("subhankar0810@gmail.com")->send(new InventoryMail($rows));
            $this->info('Mail send some product are less then 10');
        }


        return 0;
    }
}
///* * * * * php /path-to-your-project/artisan inventory:update
