<?php

use Illuminate\Http\Request;

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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::post('register','ApiController@register');
Route::post('login','ApiController@userlogin');

Route::get('getTopHotel','ApiController@getTopHotel');

Route::get('getHotel','ApiController@getHotel');
Route::get('getHotelMenu/{id}','ApiController@getHotelMenuApp');
Route::post('getCartData','ApiController@getCartData');

Route::get('getHomeCategory','ApiController@getHomeCategory');
Route::get('getHomeCategoryItem/{c_id}','ApiController@getHomeCategoryItem');

Route::get('appSearch/{query}','ApiController@appSearch');

Route::get('getUserAddress/{id}','ApiController@getUserAddress');
Route::post('saveUserAddress','ApiController@saveUserAddress');

Route::post('placeOrder','ApiController@placeOrder');
Route::get('getUserOrder/{id}','ApiController@getUserOrder');
Route::get('getOrderDetail/{oid}','ApiController@getOrderDetail');

Route::get('getVersionCode','ApiController@getVersionCode');
//Admin App

Route::get('getPendingOrder','ApiController@getPendingOrder');
Route::get('getRider','ApiController@getRider');
Route::get('msgSend/{cname}/{hname}','ApiController@msgSend');
Route::get('getAllOrder','ApiController@getAllOrder');
Route::post('setRider','ApiController@setRider');
Route::get('getAdminOrderDetail/{oid}','ApiController@getAdminOrderDetail');
