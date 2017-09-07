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

Route::get('/', function () {
    //return view('welcome');
	return redirect('shop');
});

Auth::routes();

/*Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::post('login', '\App\Http\Controllers\Auth\LoginController@postLogin');*/

Route::get('/home', 'HomeController@index')->name('home');

Route::resource('shop', 'ProductController', ['only' => ['index', 'show']]);

Route::resource('cart', 'CartController');
Route::delete('emptyCart', 'CartController@emptyCart');
Route::post('switchToWishlist/{id}', 'CartController@switchToWishlist');

Route::resource('wishlist', 'WishlistController');
Route::delete('emptyWishlist', 'WishlistController@emptyWishlist');
Route::post('switchToCart/{id}', 'WishlistController@switchToCart');


Route::get('order', ['as' => 'order', 'uses' => 'PagesController@getOrder']);
Route::post('order', ['as' => 'order-post', 'uses' => 'PagesController@postOrder']);
