<?php

use App\Http\Controllers\CurrencyController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('get_token', [CurrencyController::class, 'createUser']);

Route::middleware('auth:sanctum')->group( function () {
    Route::controller(CurrencyController::class)->group(function () {
        Route::get('/currencies/{date}', 'getCurrencies')->name('get_currencies');
        Route::get('/currency/{name}', 'getCurrency')->name('get_currency');
    });
});
