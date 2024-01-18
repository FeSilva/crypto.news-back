<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\ticker_binance;
use App\Models\ticker_huobi;
use App\Models\ticker_mercado;

class TickerExchangesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'get:ticker-exchange';

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
      $now = now();
      foreach($this->exchanges() as $slug) {
        $ccxt = "\\ccxt\\".$slug;
        $exchange = new $ccxt;
        print_r("Iniciando a exchange ". $slug. "\n");
        switch($slug) {
          case 'mercado':
            $coins =  $exchange->load_markets();
            break;
          default:
            $coins = $exchange->fetch_tickers();
            break;
        }

        foreach ($coins as $symbol => $coin) {
          print_r("lendo ativo ". $coin['symbol']. "\n");
          switch($slug) {
            case 'mercado':
              $coin = $exchange->fetch_ticker($coin['symbol']);
              break;
          }

          if ($coin['close'] > 0)
            $data[] = [
              'price' => $coin['close'],
              'currency'=> $coin['symbol'],
              'change' => $coin['change'] ?? null,
              'percentage' => $coin['percentage'] ?? null,
              'volume' => $coin['baseVolume'] ?? null,
              'created_at' => $now
            ];
        }

        print_r("Finalizando exchange \n");
        $model = 'App\Models\ticker_' . "$slug";
        $model::where("created_at","<", $now)->delete();
        $model::insert($data);
        print_r("--------------------- DADOS INSERIDOS ------------------------- \n");
      }
    }

    private function exchanges()
    {
      return ['binance','huobi','mercado'];
    }
}
