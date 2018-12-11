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

// Route::post('notify', 'Api\APILogicController@notify');
// Route::post('clientlog', 'Api\APILogicController@storeClientLog');

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



/*
 |
 |----------------------------------------------------------------------------------
 | USER AUTHENTICATED
 |----------------------------------------------------------------------------------
 |
 */

// Website API
Route::group(['prefix' => 'website', 'middleware' => 'jwt.auth'], function() {
	// Securities API
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

	Route::get('/', 'Api\Users\WebsiteController@index');
	Route::put('/', 'Api\Users\WebsiteController@update');
	
	Route::get('/{website}', 'Api\Users\WebsiteController@show');
	Route::put('/{website}', 'Api\Users\WebsiteController@update');

	// Log
	Route::get('/{website}/log', 'Api\Users\LogController@index');
	Route::get('/{website}/logs/{log}', 'Api\Users\LogController@show');

	// Live Traffic
	Route::get('/{website}/live-traffic', 'Api\Users\LiveTrafficController@index');

	// Ban API
	Route::get('/{website}/banned-ips', 'Api\Users\BanController@getBannedIPs');
	Route::get('/{website}/banned-countries', 'Api\Users\BanController@getBannedCountries');
});


// User Api
Route::group(['prefix' => 'user', 'middleware' => 'jwt.auth'], function() {
	Route::get('/', 'Api\Users\UserController@index');
	Route::put('/', 'Api\Users\UserController@update');

	// Change password
	Route::post('/change-password', 'Api\Users\UserController@changePassword');

	Route::get('/{user}/website', 'Api\Admin\WebsiteController@indexByUserId');
	
	// Search
	Route::get('search', 'Api\Users\SearchController@search');
});

/*
																					|
 -----------------------------------------------------------------------------------|
 															 END USER AUTHENTICATED |
 -----------------------------------------------------------------------------------|
 																					|
 */


/*
 |
 |----------------------------------------------------------------------------------
 | ADMIN
 |----------------------------------------------------------------------------------
 |
 */

Route::group(['prefix' => 'admin', 'middleware' => 'jwt.auth'], function() {
	
	Route::group(['prefix' => 'user', 'middleware' => 'jwt.auth'], function() {
		// User
		Route::get('/', 'Api\Admin\UserController@index');
		Route::post('/', 'Api\Admin\UserController@store');
		Route::get('/{user}', 'Api\Admin\UserController@show');
		Route::put('/{user}', 'Api\Admin\UserController@update');
		Route::delete('/{user}', 'Api\Admin\UserController@destroy');

		// Change password
		Route::post('/{user}/change-password', 'Api\Admin\UserController@changePassword');

		// Website
		Route::get('/{user}/website', 'Api\Admin\WebsiteController@indexByUserId');
		Route::post('/{user}/website', 'Api\Admin\WebsiteController@storeWithUserId');
	});

	Route::group(['prefix' => 'website', 'middleware' => 'jwt.auth'], function() {
		// Website CRUD Api
		Route::get('/', 'Api\Admin\WebsiteController@index');
		Route::post('/', 'Api\Admin\WebsiteController@store');
		Route::get('/{website}', 'Api\Admin\WebsiteController@show');
		Route::put('/{website}', 'Api\Admin\WebsiteController@update');
		Route::delete('/{website}', 'Api\Admin\UserController@destroy');

		// Log get API
		Route::get('/{website}/log', 'Api\Admin\LogController@indexByWebsiteId');
	});

	Route::get('log', 'Api\Admin\LogController@index');
	Route::post('create-user-website', 'Api\Admin\UserController@createUserAndWebsite');

	// Search
	Route::get('search', 'Api\Admin\SearchController@search');
});

/*
																					|
 -----------------------------------------------------------------------------------|
 																		  END ADMIN |
 -----------------------------------------------------------------------------------|
 																					|
 */


/*
 |
 |----------------------------------------------------------------------------------
 | PUBLIC
 |----------------------------------------------------------------------------------
 |
 */

// User Login
Route::post('login', 'Api\APILoginController@login');

// Log
Route::post('log', 'Api\Publics\LogController@store');

// Public Key
Route::post('check/public-key', 'Api\Publics\PublicKeyController@checkAndActivatePublicKey');

// IP Ban
Route::get('ip/check', 'Api\Publics\BanController@ipCheck');
Route::post('ip/ban', 'Api\Publics\BanController@banIP');

// Country Ban
Route::get('country/check', 'Api\Publics\BanController@countryCheck');
Route::post('country/ban', 'Api\Publics\BanController@banCountry');

// Live Traffic
Route::get('live-traffic', 'Api\Publics\LiveTrafficController@index');
Route::post('live-traffic', 'Api\Publics\LiveTrafficController@store');

// Change password
Route::post('change-password', 'Api\Publics\PublicKeyController@changePassword');

// Search
Route::get('search', 'Api\Publics\SearchController@search');

/*
																					|
 -----------------------------------------------------------------------------------|
 																		 END PUBLIC |
 -----------------------------------------------------------------------------------|
 																					|
 */