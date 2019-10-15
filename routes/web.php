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
Route::get('/payment', function () {
    return view('mobile_users/payment');
});
Route::post('/paymentcomplete', function () {
    return view('mobile_users/paymentcomplete');
});
Route::get('/policy', function () {
    return view('policy');
});
Route::get('/forbidden', function () {
    return view('other/forbidden');
})->name('forbidden');


//AUTHENTICATION
Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');
Route::get('/logout', '\App\Http\Controllers\Auth\LoginController@logout');
Route::post('login', 'Auth\LoginController@login');


//PASSWORD RESET
Route::get('password/reset', 'Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
Route::get('password/reset/{token}', 'Auth\ResetPasswordController@showResetForm')->name('password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
Route::post('password/reset', 'Auth\ResetPasswordController@reset')->name('password.update');


//EMAIL VERIFICATION
Route::get('email/verify', 'Auth\VerificationController@show')->name('verification.notice');
Route::get('email/verify/{id}', 'Auth\VerificationController@verify')->name('verification.verify');
Route::get('email/resend', 'Auth\VerificationController@resend')->name('verification.resend');


Route::get('/home', 'HomeController@index')->name('home');


Route::group(['middleware' => ['authenticated']], function () {

    Route::get('/account', ['as' => 'account.index', 'uses' => 'AccountController@index']);

    Route::group(['middleware' => ['any_role']], function () {


        Route::get('/company-services', function () {
            return view('companies/services');
        })->name('company.services');

        Route::get('/company-edit', function () {
            return view('profile/company');
        })->name('profile.company');

        Route::get('/send-user', function () {
            return view('mobile_users/send-user');
        })->name('send.user');

        Route::get('/return', function () {
            return view('companies/return');
        });


        Route::get('/partner-share-services', ['as' => 'share.services', 'uses' => 'PartnerController@shareServices']);

        //TRANSACTIONS
        Route::get('/transactions/payments/all', 'TransactionController@paymentsAll')->name('transactions.payments.all');
        Route::get('/transactions/return/all', 'TransactionController@returnAll')->name('transactions.return.all');
        Route::post('/transactions-search-go', 'TransactionController@searchGo')->name('transactions.search.go');
        Route::get('/transactions/payments', 'TransactionController@payments')->name('transactions.payments');
        Route::get('/transactions/report1', 'TransactionController@report')->name('transactions.report1');
        Route::get('/transactions/testReport', 'TransactionController@reportTest')->name('transactions.testReport');
        Route::get('/transactions-search', 'TransactionController@search')->name('transactions.search');
        Route::get('/transactions/return', 'TransactionController@return')->name('transactions.return');
        Route::get('/transactions-search-make', 'TransactionController@searchMake');


        //USE TAKONS
        Route::get('/transactions/use', 'TransactionController@use')->name('transactions.use');
        Route::get('/transactions/use/all', 'TransactionController@useAll')->name('transactions.use.all');
        //    Route::get('/transactions/use/all/{id}', 'TransactionController@useAll')->name('transactions.use.all');


        //PARTNERS
        Route::get('/all-partners', ['as' => 'all.partners', 'uses' => 'PartnerController@getPartners']);


        //SERVICES
        Route::post('/edit-service-save', ['as' => 'edit.service.save', 'uses' => 'ServiceController@editSave']);
        Route::get('/my-services', ['as' => 'all.my_services', 'uses' => 'ServiceController@getMyServices']);
        Route::get('/edit-service', ['as' => 'edit.service', 'uses' => 'ServiceController@edit']);


        //COMPANIES
        Route::get('/get-services', ['as' => 'company.get.services', 'uses' => 'CompanyController@getServices']);
        Route::get('/all-companies', ['as' => 'all.companies', 'uses' => 'CompanyController@all']);


        Route::get('/get-by-ids', ['as' => 'get.by.ids', 'uses' => 'MobileUserController@getUsersByIds']);
        Route::post('/send-takons', ['as' => 'send.takons', 'uses' => 'CompanyController@sendTakons']);
        Route::get('/generate-qr', ['as' => 'generate-qr', 'uses' => 'UserController@generateQR']);
        Route::post('/editcompany', ['as' => 'edit.company', 'uses' => 'CompanyController@edit']);
        Route::post('/send', ['as' => 'send', 'uses' => 'MobileUserController@send']);


        Route::get('/share-services', ['as' => 'share.services', 'uses' => 'CompanyController@shareServices']);
        Route::get('/get-partners-services', ['uses' => 'PartnerController@getPartnersServices']);
        Route::get('/partners-services', ['uses' => 'PartnerController@getServicesPage']);


        Route::get('/return-takon', ['as' => 'return.takon', 'uses' => 'CompanyController@returnTakon']);
        Route::get('/get-return', ['as' => 'get.return', 'uses' => 'CompanyController@getReturn']);
        Route::get('/edit-user', ['as' => 'edit.user', 'uses' => 'UserController@edit']);


        Route::post('/send-to-user', ['uses' => 'MobileUserController@sendUser'])->name('send.to.user');//add-user-group


        Route::post('/add-user-finish', ['uses' => 'MobileUserController@addUserFinish']);//add-user-group
        Route::get('/add-user-group', ['uses' => 'MobileUserController@addUserGroup']);//add-user-group


        Route::get('/all-mobile-users', ['as' => 'all.mobile_users', 'uses' => 'MobileUserController@all']);
        Route::post('/save-group', ['as' => 'save.group', 'uses' => 'MobileUserController@saveGroup']);
        Route::post('/save-user', ['as' => 'store.user', 'uses' => 'UserController@update']);
        Route::post('/edit-partner', ['as' => 'edit.partner', 'uses' => 'PartnerController@save']);

        Route::group(['middleware' => ['is_superadmin']], function () {

            //REPORTS
            Route::get('/report-by-company', ['as' => 'report.by.company', 'uses' => 'ReportController@reportByCompany']);
            Route::get('/api/report-by-company', ['as' => 'ajax.report.by.company', 'uses' => 'Ajax\ReportController@reportByCompany']);
            Route::post('/api/report-by-company-json', ['as' => 'ajax.report.by.companyJson', 'uses' => 'Ajax\ReportController@reportByCompanyAjax']);

            //SERVICES
            Route::get('/services/moderation', function () {
                return view('services/moderation');
            })->name('services.moderation');


            //ORDERS
            Route::get('/orders', function () {
                return view('orders/index');
            });


            //PARTNERS
            Route::post('/store-partner', ['as' => 'store.partner', 'uses' => 'PartnerController@store']);
            Route::post('/partner-share', ['as' => 'partner.share', 'uses' => 'PartnerController@share']);

            Route::get('/add-partner', ['as' => 'add.partner', 'uses' => 'PartnerController@create']);
            Route::get('/partners', ['as' => 'partners_list', 'uses' => 'PartnerController@index']);


            //COMPANIES
            Route::get('/create-company', ['as' => 'create.company', 'uses' => 'CompanyController@create']);
            Route::post('/store-company', ['as' => 'store.company', 'uses' => 'CompanyController@store']);
            Route::get('/companies', 'CompanyController@index')->name('companies.index');


            //MOBILE_USERS
            Route::get('/mobile_users', ['as' => 'mobile_users', 'uses' => 'MobileUserController@index']);

            Route::post('/save-orders', ['as' => 'save.order', 'uses' => 'OrderController@save']);
            Route::get('/orders/view', ['as' => 'orders.view', 'uses' => 'OrderController@show']);
            Route::get('/all-orders', ['as' => 'all.orders', 'uses' => 'OrderController@all']);


            //TRANSACTIONS
            Route::get('/transactions/admin/more/get', 'TransactionController@adminMoreGet')->name('transactions.admin.more.get');
            Route::get('/transactions/admin/etc/get', 'TransactionController@adminEtcGet')->name('transactions.admin.etc.get');
            Route::get('/transactions/admin/more', 'TransactionController@adminMore')->name('transactions.admin.more');
            Route::get('/transactions/admin/all', 'TransactionController@adminAll')->name('transactions.admin.all');
            Route::get('/transactions/admin/etc', 'TransactionController@adminEtc')->name('transactions.admin.etc');
            Route::get('/transactions', 'TransactionController@index')->name('transactions.admin');


            //SERVICES
            Route::get('/moderation-services', ['as' => 'moderation.services', 'uses' => 'ServiceController@moderationList']);
            Route::post('/moderate-service', ['as' => 'moderate.service', 'uses' => 'ServiceController@moderate']);
            Route::get('/services/view', ['as' => 'services.view', 'uses' => 'ServiceController@show']);
        });


        Route::group(['middleware' => ['is_company_admin']], function () {


            //COMPANIES
            Route::get('/buy-service', function () {
                return view('companies/buy');
            })->name('buy.service');


            Route::get('/buy-current-service', ['as' => 'buy.current.service', 'uses' => 'PartnerController@buyCurrentService']);
            Route::post('/finish', ['as' => 'finish.return', 'uses' => 'CompanyController@finish']);
            Route::post('/buy', ['as' => 'buy', 'uses' => 'PartnerController@buyService']);
            Route::post('/share', ['as' => 'share', 'uses' => 'CompanyController@share']);


            //TRANSACTIONS
            Route::get('/transactions/company/more/get', 'TransactionController@companyMoreGet')->name('transactions.company.more.get');
            Route::get('/transactions/company/etc/get', 'TransactionController@companyEtcGet')->name('transactions.company.etc.get');
            Route::get('/transactions/company/more', 'TransactionController@companyMore')->name('transactions.company.more');
            Route::get('/transactions/company/all', 'TransactionController@companyAll')->name('transactions.company.all');
            Route::get('/transactions/company/etc', 'TransactionController@companyEtc')->name('transactions.company.etc');
            Route::get('/transactions/company', 'TransactionController@company')->name('transactions.company');


            //MOBILE_USERS
            Route::post('/add-users-group', ['as' => 'add.users.group', 'uses' => 'MobileUserController@addUserGroup']);
            Route::post('/remove-group', ['as' => 'remove.group', 'uses' => 'MobileUserController@removeGroup']);
            Route::get('/create-group', ['as' => 'create.group', 'uses' => 'MobileUserController@createGroup']);
            Route::post('/search-user', ['as' => 'search.user', 'uses' => 'MobileUserController@searchUser']);
            Route::post('/store-group', ['as' => 'store.group', 'uses' => 'MobileUserController@storeGroup']);
            Route::post('/set-name', ['as' => 'set.name', 'uses' => 'MobileUserController@setName']);

            Route::post('/remove-user', ['as' => 'remove.user', 'uses' => 'MobileUserController@removeUser']);
            Route::get('get-groups/', ['as' => 'get.groups', 'uses' => 'MobileUserController@getGroups']);
            Route::get('/add-user', ['uses' => 'MobileUserController@addUser'])->name('add.user');//add-user-group
            Route::get('/groups', ['as' => 'groups', 'uses' => 'MobileUserController@groups']);
            Route::get('/choose-group', ['uses' => 'MobileUserController@chooseGroup']);


            //REPORTS
            Route::get('/report', ['as' => 'report', 'uses' => 'CompanyController@report']);
            Route::get('/test-report', ['as' => 'reportTest', 'uses' => 'CompanyController@reportTest']);
        });


        Route::group(['middleware' => ['role']], function () {


            //PROFILE
            Route::get('/profile', function () {
                return view('profile/index');
            });


            //SERVICES
            Route::get('/create-service', ['as' => 'create.service', 'uses' => 'ServiceController@create']);
            Route::post('/store-service', ['as' => 'store.service', 'uses' => 'ServiceController@store']);
            Route::get('/services', 'ServiceController@index')->name('services.index');


            //TRANSACTIONS
            Route::get('/transactions/partner/more/get', 'TransactionController@partnerMoreGet')->name('transactions.partner.more.get');
            Route::get('/transactions/partner/etc/get', 'TransactionController@partnerEtcGet')->name('transactions.partner.etc.get');
            Route::get('/transactions/partner/more', 'TransactionController@partnerMore')->name('transactions.partner.more');
            Route::get('/transactions/partner/all', 'TransactionController@partnerAll')->name('transactions.partner.all');
            Route::get('/transactions/partner/etc', 'TransactionController@partnerEtc')->name('transactions.partner.etc');
            Route::get('/transactions/partner', 'TransactionController@partner')->name('transactions.partner');


            //EMPLOYEES
            Route::get('/create-employee', ['as' => 'create.employee', 'uses' => 'UserController@create']);
            Route::post('/store-employee', ['as' => 'store.employee', 'uses' => 'UserController@store']);
            Route::get('/my-employees', ['as' => 'all.employees', 'uses' => 'UserController@all']);
            Route::get('/employees', 'UserController@index')->name('emplyees.index');
        });
    });
});

