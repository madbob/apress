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

Route::get('/', 'CommonsController@homepage');
Route::get('dispatch', 'CommonsController@dispatchAll');
Route::get('dashboard', 'DashboardController@homepage');
Route::post('dashboard/save', 'DashboardController@save');

Route::get('login', 'AuthController@login')->name('twitter.login');
Route::get('twitter/callback', 'AuthController@callback')->name('twitter.callback');
Route::get('logout', 'AuthController@logout')->name('twitter.logout');

Route::get('twitter/error', ['as' => 'twitter.error', function(){
    echo "error";
}]);
