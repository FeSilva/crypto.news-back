<?php

namespace App\Services;

use App\Library\CcxtLibrary as library;

class CcxtServices
{

  protected $exchange;
  protected $services;

  public function __construct($exchange)
  {
    $this->exchange = $exchange;
    $this->services = new library($exchange);
  }

  public function QuoteNowCommand()
  {
    try {
      return $this->services->marketData();
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function fetchOhlcv($limit, $coinsFilter, $quotePrice, $walletPrice)
  {
    try {
      $itemSum = 0;
      $item = 0;
      $aDayCoins['percentWalletPeriod'] = 0;
      $priceWalletNow = 0;
      $ids = 0;
      $detailWallet = [];
      foreach ($coinsFilter as $symbol => $percent) {
        $fetchOhlcv = $this->services->fetchOhlcv($symbol, $limit);
        if (empty($fetchOhlcv["error"])) {
          $coinsForFilter[$symbol] = $fetchOhlcv;
        }
        $cashCoinInWallet = ($percent * $walletPrice) / 100;
        foreach ($coinsForFilter as $symbols => $values) {
          foreach ($values as $date => $data) {
            $priceOld = $cashCoinInWallet;
            $price = ($cashCoinInWallet + ($cashCoinInWallet / 100) * $data['percentage']);
            $cashCoinInWallet = $price;
            $sumArray[$data['date']]['percentage'][] = $data['percentage'];
            $sumArray[$data['date']]['price'][$symbol] = $cashCoinInWallet;
            $detailWallet[$data['date']][$symbol] = ['id'=> $ids++,'details' => (array)$data, 'price' => $cashCoinInWallet, 'investiment' => $priceOld, 'percent' => 0];
            $itemSum++;
          }
        }
      }

      $cashCoinInWallet = ($percent * $walletPrice) / 100;
      foreach ($sumArray as $date => $value) {
        $totalPercentage = 0;
        foreach ($detailWallet[$date] as $symbol => $details) {
          $BTCPercentage = ($details['price'] / array_sum($value['price'])) * 100;
          $detailWallet[$date][$symbol]['percent'] = $BTCPercentage;
        }
        $aDayCoins['data']['assets'][$item]['percentage'] = array_sum($value['percentage']);
        $aDayCoins['data']['assets'][$item]['balance'] = ($walletPrice + ($walletPrice / 100) * array_sum($value['percentage']));
        $aDayCoins['data']['assets'][$item]['date'] = $date;
        $item++;
      }

      $aDayCoins['priceWallet'] = ($walletPrice + ($walletPrice / 100) * array_sum($value['percentage']));
      $aDayCoins['percentWalletPeriod'] = $this->diffPercent($walletPrice, $aDayCoins['priceWallet']);
      $aDayCoins['data']['details'] = $detailWallet;
      return $aDayCoins;
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }


  private function diffPercent($valor_antigo, $valor_novo)
  {
    if ($valor_antigo == 0) {
      return "Impossível calcular o percentual de diferença, o valor antigo é zero.";
    }

    $diferenca = $valor_novo - $valor_antigo;
    $percentual = ($diferenca / $valor_antigo) * 100;

    return $percentual;
  }

  public function detailsExchange()
  {
    try {
      $coins = [];
      $model = 'App\Models\ticker_' . "$this->exchange";
      $assets = $model::get();
      foreach ($assets as $asset) {
        $symbol = explode("/", $asset->currency);
        if (isset($symbol[1]))
          $details = $this->assetsInExchange($symbol, $asset);
        if ($details)
          $coins[$asset->currency] = $details;
      }
      return [
        'name' => $this->exchange,
        'imgUrl' => $this->services->logo(),
        'assets' => $coins
      ];
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  private function assetsInExchange($symbol, $asset)
  {
    switch ($this->exchange) {
      case 'mercado':
        switch ($symbol[1]) {
          case 'BRL':
            return $asset;
            break;
        }
        break;
      case 'binance':
        switch ($symbol[1]) {
          case 'USDT':
            return  $asset;
            break;
        }
        break;
      case 'huobi':
        switch ($symbol[1]) {
          case 'USDT':
            return $asset;
            break;
        }
        break;
    }
  }
}
