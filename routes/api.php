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
Route::post('/paymenthandle', ['uses' => 'ApiController@paymentHandle']);
Route::post('/test', ['uses' => 'ApiController@sendTest']);

Route::post('/m_register', ['uses' => 'ApiController@auth']);
Route::post('/m_login', ['uses' => 'ApiController@checkCode']);
Route::group(['middleware' => ['token']], function () {

    Route::post('/getsubscriptions', ['uses' => 'ApiController@getSubscriptions']);
    Route::post('/getorganizations', ['uses' => 'ApiController@getPartners']);
    Route::post('/addsubscription', ['uses' => 'ApiController@subscribe']);
    Route::post('/getservices', ['uses' => 'ApiController@getServices']);
    Route::post('/gettakons', ['uses' => 'ApiController@getUsersServices']);
    Route::post('/send_takon', ['uses' => 'ApiController@sendTakon']);
    Route::post('/scan', ['uses' => 'ApiController@scan']);
    Route::post('/qrgenerate', ['uses' => 'ApiController@generateQR']);
    Route::post('/qr_scan_for_presenting', ['uses' => 'ApiController@scanQR']);
    Route::post('/login', ['uses' => 'ApiController@logIn']);
    Route::post('/qrscan', ['uses' => 'ApiController@scanCashierQR']);
    Route::post('/gethistory', ['uses' => 'ApiController@getHistory']);
    Route::post('/getarchive', ['uses' => 'ApiController@getArchive']);
    Route::post('/deletesubscription', ['uses' => 'ApiController@removeSubscription']);
    Route::post('/setpushid', ['uses' => 'ApiController@setPushId']);
    Route::post('/pay', ['uses' => 'ApiController@pay']);
    Route::post('/get-account', ['uses' => 'ApiController@getAccount']);
    Route::post('/set-name', ['uses' => 'ApiController@setName']);
    Route::post('/paymentcomplete', ['uses' => 'ApiController@paymentHandle']);
    Route::get('/getCards', ['uses' => 'ApiController@getCards']);

});

//scanCashierQR
