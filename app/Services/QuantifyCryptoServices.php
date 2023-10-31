<?php

namespace App\Services;

use App\Library\QuantifyCrypto;
use App\Models\CoinDetails;
use Illuminate\Support\Facades\DB;
Class QuantifyCryptoServices {

  protected $QuantifyLibrary;
  public function __construct()
  {
    $this->QuantifyLibrary = new QuantifyCrypto();
  }

  public function coinDetailsCommand() {
    $listCoins = $this->QuantifyLibrary->getListAllCoins();
    $index = 0; // Inicializa o índice
    $coinsArray = [];

    while ($index < count($listCoins['data'])) {
      $coins = $listCoins['data'][$index];
      $details = $this->QuantifyLibrary->getCoinDetails($coins['coin_symbol']);
      if (isset($details['data'])) {
          $coinsArray[] = [
            'currency' => $details['data']['coin_symbol'],
            'details'=> json_encode($details['data']),
            'created_at' => now()
          ];
          $index++;
          dump("sucesso:". $index);
      } else {
          dump("Too Many REQUEST..");
          sleep(60);
      }
    }
    CoinDetails::where("currency","!=","TABLE")->delete();
    CoinDetails::insert($coinsArray);
    self::mountTable();
    print_r("---LISTA MONTADA---");
  }


  public static function mountTable()
  {
      try {
          $coins = [];
          $coinDetails = DB::SELECT("SELECT *
          FROM
              coin_details
          WHERE
              currency != 'TABLE'
          ");
          foreach ($coinDetails as $coinDetail){
              $details['data'][] = json_decode($coinDetail->details);
          }
          CoinDetails::updateOrCreate(['currency' => 'TABLE'],['details' => json_encode($details), 'created_at' => now(),'updated_at' => now()]);
      } catch (\Exception $e) {
          return $e->getMessage();
      }
  }


}
