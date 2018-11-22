<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

// PUBLIC ENDPOINTS
Route::post('login', 'Api\APILoginController@login');
Route::post('notify', 'Api\APILogicController@notify');

// PROTECTED USER ROUTES
Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'user',
], function () {

    Route::get('/', 'Api\APILoginController@me');
    Route::get('websites', 'Api\APILogicController@getUserWebsites');
    Route::get('logout', 'Api\APILoginController@logout');

});


// Route::middleware('jwt.auth')->get('users', function () {
//     return auth('api')->user();
// });

// Securities API
Route::group(['prefix' => 'website', 'middleware' => 'jwt.auth'], function() {
	$securities = [
		'content-protection' => ['function'],
		'ad-blocker-protection' => null,
		'dos-protection' => null,
		'proxy-protection' => null,
		'sql-protection' => null,
		'spam-protection' => null,
		'bot-protection' => null
	];
	Route::get('/{website}/security', 'Api\Users\SecurityController@getSecurities');
	foreach( $securities as $security => $fields ) {
		$uppercaseWords = str_replace('-', ' ', $security);
		$uppercaseWords = ucwords($uppercaseWords);
		$uppercaseWords = str_replace(' ', '', $uppercaseWords);
		$camelCase = lcfirst($uppercaseWords);
		Route::get('/{website}/' . $security, 'Api\Users\SecurityController@get' . $uppercaseWords);
		Route::post('/{website}/' . $security, 'Api\Users\SecurityController@set' . $uppercaseWords);
		if ( !empty($fields) ) {
			Route::post('/{website}/' . $security . '/{fieldName}/{fieldId}', 'Api\Users\SecurityController@set'  . $uppercaseWords . 'JSONFieldById');
		}
	}

	Route::get('/', 'Api\Users\UserController@index');
	Route::get('/{website}', 'Api\Users\WebsiteController@show');
	Route::put('/{website}', 'Api\Users\WebsiteController@update');

	// IP Ban
	Route::get('/{website}/ip/check', 'Api\Users\BanController@ipCheck');
	Route::post('/{website}/ip/ban', 'Api\Users\BanController@banIP');

	// Country Ban
	Route::get('/{website}/country/check', 'Api\Users\BanController@countryCheck');
	Route::post('/{website}/country/ban', 'Api\Users\BanController@banCountry');

	// Live Traffic
	Route::get('/{website}/live-traffic', 'Api\Users\LiveTrafficController@index');

	// Log
	Route::get('/{website}/log', 'Api\Users\LogController@index');
	
	Route::get('/{website}/logs/{log}', 'Api\Users\LogController@show');

});

// User Api
Route::group(['prefix' => 'user', 'middleware' => 'jwt.auth'], function() {
	Route::get('/', 'Api\Users\UserController@index');
	Route::get('/{user}', 'Api\Users\UserController@show');
	Route::put('/{user}', 'Api\Users\UserController@update');

	Route::get('/{user}/website', 'Api\Admin\WebsiteController@indexByUserId');
});


// Website Api
Route::group(['prefix' => 'website', 'middleware' => 'jwt.auth'], function() {
	Route::get('/', 'Api\Users\UserController@index');
	Route::get('/{website}', 'Api\Users\WebsiteController@show');
	Route::put('/{website}', 'Api\Users\WebsiteController@update');
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


// Public
Route::post('website/{website}/log', 'Api\Users\LogController@storeLog');
Route::get('check/public-key', 'Api\PublicController@checkPublicKey');