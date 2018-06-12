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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'MovieController@index');

Route::get('/home', 'MovieController@index');

Auth::routes();

// Route::get('/home', 'HomeController@index')->name('home');

Route::resource('movies', 'MovieController');
Route::resource('casts', 'CastController');

Route::post('checkout', 'CheckoutController@checkout')->name('checkout');
// Route::post('checkout', 'MovieOrdersController@checkout')->name('checkout');
// Route::post('pay', 'MovieOrdersController@pay')->name('checkout.pay');


Route::post('movies/{movie}/orders', 'MovieOrdersController@store');

Route::get('orders/{confirmationNumber}', 'OrdersController@show');
Route::get('search', 'CastController@search')->name('search');
