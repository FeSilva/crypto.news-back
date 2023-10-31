<?php

namespace App\Services;
use App\Models\Repository\CoinDetailsRepository;
Class CoinDetailsServices {

  protected $repository;
  public function __construct()
  {
    $this->repository = new CoinDetailsRepository();
  }

  public function getCoinDetails()
  {
    $coins = $this->repository->getCoins()['details'];

    foreach (json_decode($coins)->data as $coin) {
      switch($coin->rank) {
        case 1:
        case 2:
        case 3:
        case 4:
            $top4Ranking[] = [
              'id' => $coin->id,
              'symbol' => $coin->coin_symbol,
              'rank' => $coin->rank,
              'circulacao_mercado' => $coin->circulating_supply,
              'coin_name' => $coin->coin_name,
              'marketcap' => $coin->marketcap,
              'percent_change_1h' => $coin->percent_change_1h,
              'percent_change2h' => $coin->percent_change_2h,
              'percent_change4h' => $coin->percent_change_4h,
              'percent_change24h' => $coin->percent_change_24h,
              'percent_change7d' => $coin->percent_change_7d,
              'percent_change14d' => $coin->percent_change_14d,
              'percent_change30d' => $coin->percent_change_30d,
              'percent_change_1year' => $coin->percent_change_1year,
              'coin_price' => $coin->coin_price,
              'coin_price_btc' => $coin->coin_price_btc,
            ];
          break;
      }
      $details[] = [
        'id' => $coin->id,
        'symbol' => $coin->coin_symbol,
        'rank' => $coin->rank,
        'circulacao_mercado' => $coin->circulating_supply,
        'coin_name' => $coin->coin_name,
        'marketcap' => $coin->marketcap,
        'percent_change_1h' => $coin->percent_change_1h,
        'percent_change2h' => $coin->percent_change_2h,
        'percent_change4h' => $coin->percent_change_4h,
        'percent_change24h' => $coin->percent_change_24h,
        'percent_change7d' => $coin->percent_change_7d,
        'percent_change14d' => $coin->percent_change_14d,
        'percent_change30d' => $coin->percent_change_30d,
        'percent_change_1year' => $coin->percent_change_1year,
        'coin_price' => $coin->coin_price,
        'coin_price_btc' => $coin->coin_price_btc,
      ];
    }
    return ["details" => $details, "top_four" => $top4Ranking];
  }


}
