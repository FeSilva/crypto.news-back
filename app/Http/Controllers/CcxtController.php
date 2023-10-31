<?php

namespace App\Http\Controllers;
use App\Services\CcxtServices as services;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

Class CcxtController extends Controller {

  public function getExchanges()
  {
    foreach ($this->exchanges() as $slug) {
      $service = new services($slug);
      $details[] = $service->detailsExchange();
    }
    return response()->json($details, 200);
  }

  private function exchanges()
  {
    return [
      'binance',
      'huobi',
      'bybit',
      'kraken',
      'coinbasepro',
      'mercado'
    ];
  }
}
