<?php

namespace App\Services;

use Exception;

Class NewsServices {

  public function __construct() {

  }

  public function NewsApi($data) {
    return $this->CurlNewsApi($data);
  }

  private function CurlNewsApi($search_item) {
    try {
      if(empty($search_item))
          $search_item = 'bitcoin';

      $dataAtual = date('Y-m-d');
      $dataDoisDiasAtras = date('Y-m-d', strtotime($dataAtual . ' -2 days'));
      $curl = curl_init();
      curl_setopt_array($curl, array(
        CURLOPT_URL => "https://newsapi.org/v2/everything?q=".$search_item['search_item']."&language=pt&from=".$dataDoisDiasAtras."&to=".$dataAtual,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'GET',
        CURLOPT_HTTPHEADER => array(
          'x-api-key: 0ccfabebf5d84d588a263e68e3b9f648',
          'User-Agent: kryp.news/1.0'
        ),
      ));
      $response = curl_exec($curl);
      curl_close($curl);
      return $response;
    }catch (\Exception $e) {
      return throw new Exception($e->getMessage(), 200);
    }
  }
}
