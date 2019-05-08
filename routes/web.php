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
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/partners', ['as' => 'partners_list', 'uses' => 'PartnerController@index']);
Route::get('/all-partners', ['as' => 'all.partners', 'uses' => 'PartnerController@getPartners']);
Route::get('/add-partner', ['as' => 'add.partner', 'uses' => 'PartnerController@create']);
Route::post('/store-partner', ['as' => 'store.partner', 'uses' => 'PartnerController@store']);
