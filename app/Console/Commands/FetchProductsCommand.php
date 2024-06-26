<?php

namespace App\Console\Commands;
use Illuminate\Support\Facades\Log;
use Illuminate\Console\Command;

class FetchProductsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fetch:products';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetching Market Products';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        Log::info('Çekcek');
        $controller = new \App\Http\Controllers\Back\HepsiburadaController();
        $controller->index('http://localhost/woocommerce','ck_ee434dbfe8fe80e4fbd761c2c192eb0f21ea90e4','cs_265edc200e7ab5d662953e6b9f553abef4d7946d','ara?q=iphone',22,11,16,19);
        Log::info('Çekti');
    }
}
