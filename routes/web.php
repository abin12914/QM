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
		Route::get('hr/employee/register', 'EmployeeController@register')->name('employee-register-view');
		Route::post('hr/employee/register/action', 'EmployeeController@registerAction')->name('employee-register-action');
		Route::get('hr/employee/list', 'EmployeeController@list')->name('employee-list');

		//machine
		//excavator
		Route::get('/machine/excavator/register', 'ExcavatorController@register')->name('excavator-register-view');
		Route::post('/machine/excavator/register/action', 'ExcavatorController@registerAction')->name('excavator-register-action');
		Route::get('/machine/excavator/list', 'ExcavatorController@list')->name('excavator-list');

		//jackhammer
		Route::get('/machine/jackhammer/register', 'JackhammerController@register')->name('jackhammer-register-view');
		Route::post('/machine/jackhammer/register/action', 'JackhammerController@registerAction')->name('jackhammer-register-action');
		Route::get('/machine/jackhammer/list', 'JackhammerController@list')->name('jackhammer-list');

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

		//product
		Route::get('/product/list', 'ProductController@list')->name('product-list');
	});

	//common routes
	Route::get('/dashboard', 'LoginController@dashboard')->name('user-dashboard');
	Route::get('/my/profile', 'UserController@profileView')->name('user-profile-view');
	Route::get('/logout', 'LoginController@logout')->name('logout');


});