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
	Route::get('/register', 'LoginController@register')->name('register-view');
	Route::post('/register/action', 'LoginController@registerAction')->name('register-action');

	Route::get('/', 'LoginController@login')->name('login-view');
	Route::post('/login/action', 'LoginController@loginAction')->name('login-action');
});

Route::group(['middleware' => 'auth.check'], function () {
	Route::get('/dashboard', 'LoginController@dashboard')->name('user-dashboard');
	Route::get('/logout', 'LoginController@logout')->name('logout');
});