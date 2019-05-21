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

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/forbidden', function () {
    return view('other/forbidden');
})->name('forbidden');

Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');



Route::group(['middleware' => ['authenticated']], function () {
    // Partners
    Route::get('/partners', ['as' => 'partners_list', 'uses' => 'PartnerController@index']);
    Route::get('/all-partners', ['as' => 'all.partners', 'uses' => 'PartnerController@getPartners']);
    Route::get('/add-partner', ['as' => 'add.partner', 'uses' => 'PartnerController@create']);
    Route::post('/store-partner', ['as' => 'store.partner', 'uses' => 'PartnerController@store']);
    Route::post('/edit-partner', ['as' => 'edit.partner', 'uses' => 'PartnerController@save']);
    Route::get('/partner-share-services', ['as' => 'share.services', 'uses' => 'PartnerController@shareServices']);
    Route::post('/partner-share', ['as' => 'partner.share', 'uses' => 'PartnerController@share']);



// Companies
    Route::get('/all-companies', ['as' => 'all.companies', 'uses' => 'CompanyController@all']);
    Route::get('/get-services', ['as' => 'company.get.services', 'uses' => 'CompanyController@getServices']);
    Route::get('/companies', 'CompanyController@index');
    Route::get('/create-company', ['as' => 'create.company', 'uses' => 'CompanyController@create']);
    Route::post('/store-company', ['as' => 'store.company', 'uses' => 'CompanyController@store']);
    Route::post('/send', ['as' => 'send', 'uses' => 'MobileUserController@send']);
    Route::get('/get-by-ids', ['as' => 'get.by.ids', 'uses' => 'MobileUserController@getUsersByIds']);
    Route::post('/send-takons', ['as' => 'send.takons', 'uses' => 'CompanyController@sendTakons']);

    Route::get('/company-services', function () {
        return view('companies/services');
    })->name('company.services');

    Route::get('/buy-service', function () {
        return view('companies/buy');
    })->name('buy.service')->middleware('is_company_admin');
    Route::get('/partners-services', ['uses' => 'PartnerController@getServicesPage']);
    Route::get('/get-partners-services', ['uses' => 'PartnerController@getPartnersServices']);
    Route::get('/share-services', ['as' => 'share.services', 'uses' => 'CompanyController@shareServices']);

    Route::get('/buy-current-service', ['as' => 'buy.current.service',  'uses' => 'PartnerController@buyCurrentService'])->middleware('is_company_admin');
    Route::post('/buy', ['as' => 'buy',  'uses' => 'PartnerController@buyService'])->middleware('is_company_admin');
    Route::post('/share', ['as' => 'share',  'uses' => 'CompanyController@share'])->middleware('is_company_admin');
    Route::get('/return', function () {
        return view('companies/return');
    });
    Route::get('/get-return', ['as' => 'get.return', 'uses' => 'CompanyController@getReturn']);
    Route::get('/return-takon', ['as' => 'return.takon', 'uses' => 'CompanyController@returnTakon']);
    Route::post('/finish', ['as' => 'finish.return',  'uses' => 'CompanyController@finish'])->middleware('is_company_admin');



// Mobile Users
    Route::get('/mobile_users', function () {
        return view('mobile_users/index');
    });
    Route::get('/all-mobile-users', ['as' => 'all.mobile_users', 'uses' => 'MobileUserController@all']);

// Employees
    Route::get('/employees', 'UserController@index');
    Route::get('/my-employees', ['as' => 'all.employees', 'uses' => 'UserController@all']);
    Route::get('/create-employee', ['as' => 'create.employee', 'uses' => 'UserController@create']);
    Route::post('/store-employee', ['as' => 'store.employee', 'uses' => 'UserController@store']);


// Services
    Route::get('/services', 'ServiceController@index')->middleware('role');
    Route::get('/my-services', ['as' => 'all.my_services', 'uses' => 'ServiceController@getMyServices']);
    Route::get('/create-service', ['as' => 'create.service', 'uses' => 'ServiceController@create'])->middleware('role');
    Route::post('/store-service', ['as' => 'store.service', 'uses' => 'ServiceController@store'])->middleware('role');
    Route::get('/services/moderation', function () {
        return view('services/moderation');
    })->name('services.moderation')->middleware('is_superadmin');
    Route::get('/services/view', ['as' => 'services.view', 'uses' => 'ServiceController@show']);
    Route::get('/moderation-services', ['as' => 'moderation.services', 'uses' => 'ServiceController@moderationList'])
        ->middleware('is_superadmin');
    Route::post('/moderate-service', ['as' => 'moderate.service', 'uses' => 'ServiceController@moderate'])
        ->middleware('is_superadmin');


    // Orders
    Route::get('/orders', function () {
        return view('orders/index');
    });
    Route::get('/all-orders', ['as' => 'all.orders', 'uses' => 'OrderController@all']);
    Route::post('/save-orders', ['as' => 'save.order', 'uses' => 'OrderController@save']);
    Route::get('/orders/view', ['as' => 'orders.view', 'uses' => 'OrderController@show']);


    // TODO: Create middleware for superadmin user



// Profile
    Route::get('/profile', function () {

        return view('profile/index');
    })->middleware('role');


});

