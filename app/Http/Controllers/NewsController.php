<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Services\NewsServices;
class NewsController extends Controller
{
  protected $service;
  public function __construct(NewsServices $service) {
    $this->service = $service;
  }


  public function news(Request $request) {
    return $this->service->NewsApi($request->except("_token"));
  }
}
