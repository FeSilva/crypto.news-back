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

  public function logo()
  {
    return $this->ccxt->urls['logo'];
  }
}
