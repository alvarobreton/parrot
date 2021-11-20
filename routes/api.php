<?php

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

/*Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});*/



Route::post('/user','App\Http\Controllers\UsersController@store');
/*Route::post('/products','App\Http\Controllers\ProductsController@store');
Route::post('/create_order','App\Http\Controllers\OrdersController@store');
Route::post('/order','App\Http\Controllers\OrdersController@index');
Route::post('/report','App\Http\Controllers\ReportController@index');*/

//http://127.0.0.1:8000/api/report?api_token=ji5CpRka1CYVehhq5e0PzFzk3kdYv4ylTecRgL5LjTvIKCMKv9d6mtvgm7pY

/**
 * Los siguiente endpoints necesitan API key
 * Para generar un usuario se tiene que utilzar el endpoint: user
 */

Route::middleware('auth:api')->post('/products','App\Http\Controllers\ProductsController@store');
Route::middleware('auth:api')->post('/create_order','App\Http\Controllers\OrdersController@store');
Route::middleware('auth:api')->post('/order','App\Http\Controllers\OrdersController@index');
Route::middleware('auth:api')->get('/report','App\Http\Controllers\ReportController@index');

