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

// Companies
    Route::get('/all-companies', ['as' => 'all.companies', 'uses' => 'CompanyController@all']);
    Route::get('/get-services', ['as' => 'company.get.services', 'uses' => 'CompanyController@getServices']);
    Route::get('/companies', 'CompanyController@index');
    Route::get('/create-company', ['as' => 'create.company', 'uses' => 'CompanyController@create']);
    Route::post('/store-company', ['as' => 'store.company', 'uses' => 'CompanyController@store']);
    Route::get('/company-services', function () {
        return view('companies/services');
    })->name('company.services');
    Route::get('/buy-service', function () {
        return view('companies/buy');
    })->name('buy.service')->middleware('is_company_admin');
    Route::get('/partners-services', ['uses' => 'PartnerController@getServicesPage']);
    Route::get('/get-partners-services', ['uses' => 'PartnerController@getPartnersServices']);


    Route::get('/buy-current-service', ['as' => 'buy.current.service',  'uses' => 'PartnerController@buyCurrentService'])->middleware('is_company_admin');
    Route::post('/buy', ['as' => 'buy',  'uses' => 'PartnerController@buyService'])->middleware('is_company_admin');


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
    Route::get('/services', 'ServiceController@index')->middleware('role');;
    Route::get('/my-services', ['as' => 'all.my_services', 'uses' => 'ServiceController@getMyServices']);
    Route::get('/create-service', ['as' => 'create.service', 'uses' => 'ServiceController@create'])->middleware('role');
    Route::post('/store-service', ['as' => 'store.service', 'uses' => 'ServiceController@store'])->middleware('role');

// Orders
    Route::get('/orders', function () {
        return view('orders/index');
    });
    Route::get('/all-orders', ['as' => 'all.orders', 'uses' => 'OrderController@all']);
    Route::post('/save-orders', ['as' => 'save.order', 'uses' => 'OrderController@save']);

    // TODO: Create middleware for superadmin user



// Profile
    Route::get('/profile', function () {

        return view('profile/index');
    })->middleware('role');
    Route::get('/orders/view', ['as' => 'orders.view', 'uses' => 'OrderController@show']);


});

