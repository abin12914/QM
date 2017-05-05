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
Route::group(['middleware' => 'is.guest'], function() {
	Route::get('/', 'LoginController@publicHome')->name('public-home');

	Route::get('/login', 'LoginController@login')->name('login-view');
	Route::post('/login/action', 'LoginController@loginAction')->name('login-action');
});

Route::group(['middleware' => 'auth.check'], function () {
	//superadmin routes
	Route::group(['middleware' => 'user.role:superadmin'], function () {
		Route::get('/register', 'LoginController@register')->name('user-register-view');
		Route::post('/register/action', 'LoginController@registerAction')->name('user-register-action');
	});

	//admin routes
	Route::group(['middleware' => 'user.role:superadmin'], function () {
		Route::get('staff/register', 'StaffController@register')->name('staff-register-view');
		Route::post('/staff/register/action', 'StaffController@registerAction')->name('staff-register-action');
	});

	//user routes
	Route::group(['middleware' => 'user.role:user'], function () {

	});

	//common routes
	Route::get('/dashboard', 'LoginController@dashboard')->name('user-dashboard');
	Route::get('/logout', 'LoginController@logout')->name('logout');


});