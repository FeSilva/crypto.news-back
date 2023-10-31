<?php

namespace App\Services;

use App\Library\CcxtLibrary as library;

Class CcxtServices {

  protected $exchange;
  protected $services;

  public function __construct($exchange) {
    $this->exchange = $exchange;
    $this->services = new library($exchange);
  }

  public function QuoteNowCommand()
  {
    try{
      return $this->services->marketData();
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }

  public function detailsExchange() {
    try {
      $assets = $this->services->fetchTicker();
      return [
        'name' => $this->exchange,
        'imgUrl' => $this->services->logo(),
        'assets' => $assets
      ];
    } catch (\Exception $e) {
      return $e->getMessage();
    }
  }
}
