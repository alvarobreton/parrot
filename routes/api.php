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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/user','App\Http\Controllers\UsersController@store');

Route::post('/products','App\Http\Controllers\ProductsController@store');
Route::post('/create_order','App\Http\Controllers\OrdersController@store');
Route::post('/order','App\Http\Controllers\OrdersController@index');

