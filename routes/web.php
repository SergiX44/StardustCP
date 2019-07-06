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


Auth::routes(['register' => false]);

Route::group(['middleware' => ['auth'], 'as' => 'core.'], function () {
	Route::get('/', 'HomeController@root')->name('root');
	Route::get('/home', 'HomeController@index')->name('home');

	Route::get('/configuration', 'ConfigurationController@configuration')->name('configuration');
});

