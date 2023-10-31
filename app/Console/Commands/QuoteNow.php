<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\CcxtServices;
class QuoteNow extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:quote-now';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
      foreach ($this->exchanges() as $exchange) {
        $services = new CcxtServices($exchange);
        $ticker = $services->QuoteNowCommand();
        dump($ticker);
        die;
      }
    }

    private function exchanges() {
      return [
        'binance',
        'mercado',
        'kraken',
        'huobi',
        'bybit',
        'coinbasepro',
        'okx'
      ];
    }
}
