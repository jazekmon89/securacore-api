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

// ------- Admin ---------
// User and Website CRUD Api
Route::group(['prefix' => 'user'], function() {
	Route::get('/', 'Api\Admin\UserController@index');
	Route::post('/', 'Api\Admin\UserController@store');
	Route::get('/{user}', 'Api\Admin\UserController@show');
	Route::put('/{user}', 'Api\Admin\UserController@update');
	Route::delete('/{user}', 'Api\Admin\UserController@destroy');

	Route::get('/{user}/website', 'Api\Admin\WebsiteController@indexByUserId');
	Route::post('/{user}/website', 'Api\Admin\WebsiteController@storeWithUserId');
});

// Client CRUD Api
Route::group(['prefix' => 'website'], function() {
	Route::get('/', 'Api\Admin\WebsiteController@index');
	Route::post('/', 'Api\Admin\WebsiteController@store');
	Route::get('/{website}', 'Api\Admin\WebsiteController@show');
	Route::put('/{website}', 'Api\Admin\WebsiteController@update');
	Route::delete('/{website}', 'Api\Admin\UserController@destroy');
});