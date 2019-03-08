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
    return view('welcome');
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/guest', 'GuestController@index')->name('guest');

Route::get('getFavorites', 'FavoriteController@index');

Route::get('seekFavorite', 'FavoriteController@seek');

Route::post('setFavorite/{id}/{link}', 'FavoriteController@favoriteImage')->where('link', '.*');

Route::delete('setUnfavorite/{id}', 'FavoriteController@unfavoriteImage');