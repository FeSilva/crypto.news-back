<?php

namespace App\Library;
use App\Models\QuoteNow;
Class CcxtLibrary {

  protected $exchange;
  protected $ccxt;

  public function __construct($exchange = null)
  {
    $this->exchange = $exchange;

    $endpoint = "\\ccxt\\".$exchange;
    $this->ccxt = new $endpoint;
  }

  public function marketData() {
    $tickers = $this->fetchTicker();

    foreach ($tickers as $symbol => $ticker)
    {
      switch($this->exchange) {
        case 'mercadobitcoin':
            $ticker = $this->ccxt->fetch_ticker($symbol);
            $symbol = $ticker['symbol'];
            break;
        case 'bybit':
            $symbol = explode(':', $symbol)[0];
            break;
      }
      if (isset(explode('/', $symbol)[1]) && strtoupper(explode('/', $symbol)[1]) == $this->getQuoteCurrencyByExchange($this->exchange)) {
        $baseCurrency = strtoupper(explode('/', $symbol)[0]);
        $quoteCurrency = strtoupper(explode('/', $symbol)[1]);
        $quoteInfoBid = sprintf('%f', floatval($ticker['close']));
        if ((float)$quoteInfoBid >= 0.0001) {
          $quote[] = [
            'exchange' => $this->exchange,
            'currency' => $baseCurrency,
            'quote_currency' => $quoteCurrency,
            'price' => $quoteInfoBid,
            'changeUSD' => $ticker['change'] ?? 0,
            'percentChange' => $ticker['percentage'] ?? 0,
            'created_at' => now(),
            'updated_at' => now()
          ];
        }
      }
    }

    //QuoteNow::insert($quote);
    //QuoteNow::where("exchange", $this->exchange)->where("created_at", '<', $now)->delete();
  }

  private function getQuoteCurrencyByExchange($exchangeCode)
  {
    switch ($exchangeCode) {
      case 'mercadobitcoin':
          $quoteCurrency = 'BRL';
          break;
      case 'okx':
          $quoteCurrency = 'USDC';
          break;
      case 'kraken':
      case 'huobi':
      case 'coinbasepro':
      case 'lbank':
      case 'deribit':
      case 'bybit':
      case 'bitget':
      case 'binance':
        $quoteCurrency = 'USDT';
        break;
    }
    return $quoteCurrency;
  }

  public function fetchTicker()
  {
    switch($this->exchange) {
      case 'mercado':
          return $this->ccxt->load_markets();
        break;
      default:
          return $this->ccxt->fetch_tickers();
        break;
    }
  }

  public function fetchOhlcv($symbol, $limit) {
    try {
        $pair = explode("/", $symbol);
        $ohlcv = $this->ccxt->fetch_ohlcv($symbol, '1d', null, $limit);
        foreach ($ohlcv as $candle) {
          list($timestamp, $open_price, $high_price, $low_price, $close_price, $volume) = $candle;
          $timestamp_em_milissegundos = $timestamp;
          $timestamp_em_segundos = $timestamp_em_milissegundos / 1000;
          $dataFormatada = date("d/m/Y", $timestamp_em_segundos);
          $resultDay[$dataFormatada] = [
              "currency" => $symbol,
              "quote_currency" => $pair[1],
              "price" => $close_price,
              "changePrice" => $close_price - $open_price,
              'percentage' => (($close_price - $open_price) * 100) / $close_price,
              "date" => date("Y-m-d", $timestamp_em_segundos),
              "volume" => $volume
          ];
      }
      uasort($resultDay, function($a, $b) {
          return strtotime($b["date"]) - strtotime($a["date"]);
      });
      return array_reverse($resultDay);
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }
  public function logo()
  {
    return $this->ccxt->urls['logo'];
  }
}
