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

//licence
Route::get('/licence', 'LoginController@licence')->name('licence-view');

Route::group(['middleware' => 'is.guest'], function() {
	Route::get('/', 'LoginController@publicHome')->name('public-home');

	Route::get('/login', 'LoginController@login')->name('login-view');
	Route::post('/login/action', 'LoginController@loginAction')->name('login-action');
});

Route::group(['middleware' => 'auth.check'], function () {
	//superadmin routes
	Route::group(['middleware' => ['user.role:superadmin,']], function () {
		Route::get('/user/register', 'UserController@register')->name('user-register-view');
		Route::post('/user/register/action', 'UserController@registerAction')->name('user-register-action');
		Route::get('/user/list', 'UserController@userList')->name('user-list');

		Route::get('/owner/register', 'UserController@ownerRegister')->name('owner-register-view');
		Route::post('/owner/register/action', 'UserController@ownerRegisterAction')->name('owner-register-action');
		Route::get('/owner/list', 'UserController@ownerList')->name('owner-list');

		//product
		Route::get('/product/register', 'ProductController@register')->name('product-register-view');
		Route::post('/product/register/action', 'ProductController@registerAction')->name('product-register-action');
		Route::get('/product/list/superadmin', 'ProductController@list')->name('product-list-superadmin');
	});

	//admin routes
	Route::group(['middleware' => ['user.role:admin,']], function () {
		//vehicle type
		Route::get('/vehicle-type/register', 'VehicleTypeController@register')->name('vehicle-type-register-view');
		Route::post('/vehicle-type/register/action', 'VehicleTypeController@registerAction')->name('vehicle-type-register-action');
		Route::get('/vehicle-type/list', 'VehicleTypeController@list')->name('vehicle-type-list');		
	});

	//user routes
	Route::group(['middleware' => ['user.role:user,admin']], function () {
		//account
		Route::get('/account/register', 'AccountController@register')->name('account-register-view');
		Route::post('/account/register/action', 'AccountController@registerAction')->name('account-register-action');
		Route::get('/account/list', 'AccountController@list')->name('account-list');

		//staff
		Route::get('/hr/employee/register', 'EmployeeController@register')->name('employee-register-view');
		Route::post('/hr/employee/register/action', 'EmployeeController@registerAction')->name('employee-register-action');
		Route::get('/hr/employee/list', 'EmployeeController@list')->name('employee-list');
		Route::get('/employee/get/account/{id}', 'EmployeeController@getEmployeeByaccountId')->name('employee-get-by-account-id');
		Route::get('/employee/get/employee/{id}', 'EmployeeController@getEmployeeByEmployeeId')->name('employee-get-by-employee-id');

		//machine
		//excavator
		Route::get('/machine/excavator/register', 'ExcavatorController@register')->name('excavator-register-view');
		Route::post('/machine/excavator/register/action', 'ExcavatorController@registerAction')->name('excavator-register-action');
		Route::get('/machine/excavator/list', 'ExcavatorController@list')->name('excavator-list');
		Route::get('/get/account/by/excavator/{id}', 'ExcavatorController@getAccountByExcavatorId')->name('get-account-by-excavator-id');

		//jackhammer
		Route::get('/machine/jackhammer/register', 'JackhammerController@register')->name('jackhammer-register-view');
		Route::post('/machine/jackhammer/register/action', 'JackhammerController@registerAction')->name('jackhammer-register-action');
		Route::get('/machine/jackhammer/list', 'JackhammerController@list')->name('jackhammer-list');
		Route::get('/get/account/by/jackhammer/{id}', 'JackhammerController@getAccountByJackhammerId')->name('get-account-by-jackhammer-id');

		//vehicle
		Route::get('/vehicle/register', 'VehicleController@register')->name('vehicle-register-view');
		Route::post('/vehicle/register/action', 'VehicleController@registerAction')->name('vehicle-register-action');
		Route::get('/vehicle/list', 'VehicleController@list')->name('vehicle-list');

		//sales
		Route::get('/sales/register', 'SalesController@register')->name('sales-register-view');
		Route::post('/sales/credit/register/action', 'SalesController@creditSaleRegisterAction')->name('credit-sales-register-action');
		Route::post('/sales/cash/register/action', 'SalesController@cashSaleRegisterAction')->name('cash-sales-register-action');
		Route::get('/sales/list', 'SalesController@list')->name('sales-list');
		Route::get('/sales/get/last/vehicle/{id}', 'SalesController@getLastSaleByVehicleId')->name('sale-get-last-by-vehicle-id');

		//purchases
		Route::get('/purchases/register', 'PurchasesController@register')->name('purchases-register-view');
		Route::post('/purchases/register/action', 'PurchasesController@registerAction')->name('purchases-register-action');
		Route::get('/purchases/list', 'PurchasesController@list')->name('purchases-list');

		//product
		Route::get('/product/list', 'ProductController@list')->name('product-list');

		//daily statement
		Route::get('/daily-statement/register', 'DailyStatementController@register')->name('daily-statement-register-view');
		Route::post('/daily-statement/employee/attendance/action', 'DailyStatementController@employeeAttendanceAction')->name('daily-statement-employee-attendance-action');
		Route::post('/daily-statement/excavator/readings/action', 'DailyStatementController@excavatorReadingsAction')->name('daily-statement-excavator-readings-action');
		Route::post('/daily-statement/jackhammer/readings/action', 'DailyStatementController@jackhammerReadingsAction')->name('daily-statement-jackhammer-readings-action');
		Route::get('/daily-statement/list/employee', 'DailyStatementController@employeeAttendanceList')->name('daily-statement-list-employee');
		Route::get('/daily-statement/list/excavator', 'DailyStatementController@excavatorReadingList')->name('daily-statement-list-excavator');
		Route::get('/daily-statement/list/jackhammer', 'DailyStatementController@jackhammerReadingList')->name('daily-statement-list-jackhammer');

		//monthly statement
		Route::get('/monthly-statement/register', 'MonthlyStatementController@register')->name('monthly-statement-register-view');
		Route::post('/monthly-statement/employee/salary/action', 'MonthlyStatementController@employeeSalaryAction')->name('monthly-statement-employee-salary-action');
		Route::post('/monthly-statement/excavator/rent/action', 'MonthlyStatementController@excavatorRentAction')->name('monthly-statement-excavator-rent-action');
		Route::get('/monthly-statement/list/employee', 'MonthlyStatementController@employeeSalaryList')->name('monthly-statement-list-employee');
		Route::get('/monthly-statement/list/excavator', 'MonthlyStatementController@excavatorRentList')->name('monthly-statement-list-excavator');

		//vouchers
		Route::get('/voucher/register', 'VoucherController@register')->name('voucher-register-view');
		Route::post('/cash/voucher/register/action', 'VoucherController@cashVoucherRegistrationAction')->name('cash-voucher-register-action');
		Route::post('/credit/voucher/register/action', 'VoucherController@creditVoucherRegistrationAction')->name('credit-voucher-register-action');
		Route::get('/get/details/by/account/{id}', 'VoucherController@getAccountDetailsByaccountId')->name('get-details-by-account-id');
		Route::get('/voucher/list/cash', 'VoucherController@cashVoucherList')->name('cash-voucher-list');
		Route::get('/voucher/list/credit', 'VoucherController@creditVoucherList')->name('credit-voucher-list');

		//final statement
		Route::get('/account-statement/list', 'AccountController@accountSatementSearch')->name('account-statement-list-search');
	});

	//common routes
	Route::get('/dashboard', 'LoginController@dashboard')->name('user-dashboard');
	Route::get('/my/profile', 'UserController@profileView')->name('user-profile-view');
	Route::get('/logout', 'LoginController@logout')->name('logout');


});