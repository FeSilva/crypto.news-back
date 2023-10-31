<?php

use App\Http\Controllers\CcxtController;
use App\Http\Controllers\CoinDetailsController;
use App\Http\Controllers\NewsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/



//Rotas da api Criar validação;
Route::get("/news", [NewsController::class, 'news']);
Route::get("/coin-ranking", [CoinDetailsController::class, 'CoinDetails']);

Route::get("/exchangesList", [CcxtController::class, 'getExchanges']);


Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
