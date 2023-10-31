<?php

namespace App\Models\Repository;

use App\Models\CoinDetails;

Class CoinDetailsRepository extends CoinDetails {

  public function getCoins()
  {
    return $this->where("currency",'TABLE')->first();
  }
}
