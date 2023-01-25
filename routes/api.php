<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\CurrenciesController;
use App\Http\Controllers\OrdersController;
use App\Http\Controllers\OrderActionsController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/
Route::get('/currencies', [CurrenciesController::class,'list']);

Route::get('/currencies/rates/{ISOcode}', [CurrenciesController::class,'exchangeRates'])->where('ISOcode','[A-Za-z]{3}');

Route::get('/currencies/rates/refreshby/{ISOcode}', [CurrenciesController::class,'refresh'])->where('ISOcode','[A-Za-z]{3}');

Route::get('/orders', [OrdersController::class,'list']);
Route::post('/order', [OrdersController::class,'create']);

Route::controller(OrderActionsController::class)->group(function(){
    Route::get('/order/actions','list');
    Route::get('/order/action/types','listTypes');
    Route::patch('/order/action/{action}/for/{currency}','update')->where(['currency' => '[A-Za-z]{3}','action'=>'[a-zA-Z]+']);
});