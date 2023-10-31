<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

use App\Services\CoinDetailsServices;


class CoinDetailsController extends Controller
{
  protected $services;

  public function __construct()
  {
    $this->services = new CoinDetailsServices();
  }

  public function CoinDetails()
  {
    try {
      return response()->json($this->services->getCoinDetails(), 200);
    } catch (\Exception $e) {
      return response()->json($e->getMessage(), 400);
    }
  }
}
