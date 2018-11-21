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
// User and Client CRUD Api
Route::group(['prefix' => 'user', 'middleware' => 'web'], function() {
	Route::get('/', 'Api\Users\UserController@index');
	Route::post('/', 'Api\Users\UserController@store');
	Route::get('/{user}', 'Api\Users\UserController@show');
	Route::put('/{user}', 'Api\Users\UserController@update');
	Route::delete('/{user}', 'Api\Users\UserController@destroy');

	Route::get('/{user}/client', 'Api\Users\ClientController@indexByUserId');
	Route::post('/{user}/client', 'Api\Users\ClientController@storeWithUserId');
});

// Client CRUD Api
Route::group(['prefix' => 'client', 'middleware' => 'web'], function() {
	Route::get('/', 'Api\Users\UserController@index');
	Route::get('/{client}', 'Api\Users\ClientController@show');
	Route::put('/{client}', 'Api\Users\ClientController@update');
});