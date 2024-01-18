<?php

namespace App\Http\Controllers;

use App\Services\CcxtServices as services;
use Illuminate\Http\Request;


class CcxtController extends Controller
{

  public function getExchanges()
  {
    foreach ($this->exchanges() as $slug) {
      $service = new services($slug);
      $details[] = $service->detailsExchange();
    }
    return response()->json($details, 200);
  }


  public function backTest(Request $request)
  {
    try {
      $service = new services($request->post("slug"));
      $limit = $request->post("limit"); //Days
      $quotePrice = $request->post("quotePrice");
      $walletPrice = $request->post("walletPrice");
      $coinsFilter = $request->post("allocation");
      $fetchOhlv = $service->fetchOhlcv($limit, $coinsFilter, $quotePrice, $walletPrice);
      return $fetchOhlv;
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }

  private function exchanges()
  {
    return [
      'binance',
      'huobi',
      'mercado'
    ];
  }
}
