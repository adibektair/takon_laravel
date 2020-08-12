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

Route::post('/paymenthandle', ['uses' => 'ApiController@paymentHandle']);
Route::post('/test', ['uses' => 'ApiController@sendTest']);
Route::get('/get-companies', ['uses' => 'ApiController@getAllPartners']);

Route::post('/m_register', ['uses' => 'ApiController@auth']);
Route::post('/m_login', ['uses' => 'ApiController@checkCode']);
Route::post('/login', ['uses' => 'ApiController@logIn']);

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
    Route::post('/qrscan', ['uses' => 'ApiController@scanCashierQR']);
    Route::post('/gethistory', ['uses' => 'ApiController@getHistory']);
    Route::post('/getarchive', ['uses' => 'ApiController@getArchive']);
    Route::post('/deletesubscription', ['uses' => 'ApiController@removeSubscription']);
    Route::post('/setpushid', ['uses' => 'ApiController@setPushId']);
    Route::post('/pay', ['uses' => 'ApiController@pay']);
    Route::post('/get-account', ['uses' => 'ApiController@getAccount']);
    Route::post('/set-name', ['uses' => 'ApiController@setName']);
    Route::post('/paymentcomplete', ['uses' => 'ApiController@paymentHandle']);
    Route::post('/buyTakonByCard', ['uses' => 'ApiController@payByToken']);
    Route::post('/transaction-history', ['uses' => 'ApiController@transactionHistory']);
    Route::get('/getCards', ['uses' => 'ApiController@getCards']);
    Route::post('/get-profile', ['uses' => 'ApiController@getProfile']);
    Route::post('/remove-card', ['uses' => 'ApiController@removeCardById']);
    Route::post('/set-profile', ['uses' => 'ApiController@setProfile']);
    Route::post('/get-partners', ['uses' => 'ApiController@getPartnersList']);
    Route::post('/get-partners-locations', ['uses' => 'ApiController@getPartnersLocations']);
    Route::post('/create-wallet-order', ['uses' => 'ApiController@createWalletOrder']);
    Route::post('/wallet-success', ['uses' => 'ApiController@handleSuccededWalletPayment']);
    Route::post('/wallet-fail', ['uses' => 'ApiController@handleFailedWalletPayment']);

});

Route::get('/wallet-success', function () {
	return view('success');
});
//scanCashierQR
