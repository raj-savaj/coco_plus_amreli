<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
Route::get('/Login','Controller@login');
Route::post('/Login','Controller@checkLogin');
Route::get('/chagePassword','Controller@chagePassword');
Route::post('/chagePassword','Controller@updateAdminPassword');

Route::get('/riderLogin','Controller@getRiderLogin');
Route::post('/riderLogin','Controller@checkRiderLogin');
Route::get('/chagePasswordRider','Controller@chagePasswordRider');
Route::post('/chagePasswordRider','Controller@updateRiderPassword');

Route::group(['middleware'=>'checkAdmin'],function (){

Route::get('/','Controller@getDashoard');
Route::get('/orderDetail/{id}','Controller@getOrderDetail');
// Route::get('/changeOrderStatus','Controller@changeOrderStatus');

Route::get('/allOrders','Controller@getAllOrders');

Route::get('/Hotel','Controller@getHotelDetail');

Route::get('/addHotel','Controller@getAddHotel');
Route::post('/addHotel','Controller@addHotel');
Route::get('/updateHotel/{id}','Controller@updateHotel');
Route::post('/updateHotel','Controller@updateHotelAdmin');
Route::get('/changeHotelStatus','Controller@changeHotelStatus');

Route::get('/Menu','Controller@getMenu');
Route::post('/Menu','Controller@addMenu');
Route::get('/updateMenu/{id}','Controller@updateMenu');
Route::post('/updateMenu','Controller@updateMenuAdmin');

Route::get('/hotelList/{id}','Controller@getHotelList');

Route::get('/Category','Controller@getCategory');
Route::post('/Category','Controller@addCategory');
Route::get('/updateCategory/{id}','Controller@updateCategory');
Route::post('/updateCategory','Controller@updateCategoryAdmin');

Route::get('/hotelMenu','Controller@getHotelMenu');
Route::post('/hotelMenu','Controller@addHotelMenu');
Route::get('/updateHotelMenu/{id}','Controller@updateHotelMenu');
Route::post('/updateHotelMenu','Controller@updateHotelMenuAdmin');

Route::get('/deleteHotelMenu/{id}','Controller@deleteHotelMenu');
Route::get('/Logout','Controller@logout');

Route::get('/getPendingOrderCount','Controller@getPendingOrderCount');
Route::get('/getPendingOrderDetail','Controller@getPendingOrderDetail');

});
Route::group(['middleware'=>'checkRider'],function (){
    Route::get('/rider','Controller@getRiderDetail');
    Route::get('/riderOrderDetail/{id}','Controller@getriderOrderDetail');
    Route::get('/changeOrderStatus','Controller@changeOrderStatus');

    Route::get('/LogoutRider','Controller@logoutRider');
    
    Route::get('/getPendingOrderCountRider','Controller@getPendingOrderCountRider');
    Route::get('/getPendingOrderDetailRider','Controller@getPendingOrderDetailRider');
});
