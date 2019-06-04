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

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/m_register', ['uses' => 'ApiController@auth']);
Route::post('/m_login', ['uses' => 'ApiController@checkCode']);
Route::group(['middleware' => ['authenticated']], function () {

    Route::post('/getsubscriptions', ['uses' => 'ApiController@getSubscriptions']);
    Route::post('/getorganizations', ['uses' => 'ApiController@getPartners']);
    Route::post('/addsubscription', ['uses' => 'ApiController@subscribe']);
    Route::post('/getservices', ['uses' => 'ApiController@getServices']);
    Route::post('/gettakons', ['uses' => 'ApiController@getUsersServices']);
    Route::post('/send_takon', ['uses' => 'ApiController@sendTakon']);
    Route::post('/qrgenerate', ['uses' => 'ApiController@generateQR']);
    Route::post('/qr_scan_for_presenting', ['uses' => 'ApiController@scanQR']);
    Route::post('/login', ['uses' => 'ApiController@logIn']);
    Route::post('/qrscan', ['uses' => 'ApiController@scanCashierQR']);

});

//scanCashierQR
