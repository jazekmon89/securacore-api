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


Route::post('login', 'Api\APILoginController@login');
Route::post('notify', 'Api\APILogicController@notify');

// PROTECTED CUSTOMER ROUTES
Route::group([
    'middleware' => 'jwt.auth',
    'prefix' => 'users',
], function () {

    Route::get('/', 'Api\APILoginController@me');
    Route::get('logout', 'Api\APILoginController@logout');

});


// Route::middleware('jwt.auth')->get('users', function () {
//     return auth('api')->user();
// });

// securities API
Route::group(['prefix' => 'client', 'middleware' => 'jwt.auth'], function() {
	$securities = [
		'content-protection' => ['function'],
		'ad-blocker-protection' => null,
		'dos-protection' => null,
		'proxy-protection' => null,
		'sql-protection' => null,
		'spam-protection' => null,
		'bot-protection' => null
	];
	Route::get('/{client}/security', 'Api\Users\SecurityController@getSecurities');
	foreach( $securities as $security => $fields ) {
		$uppercaseWords = str_replace('-', ' ', $security);
		$uppercaseWords = ucwords($uppercaseWords);
		$uppercaseWords = str_replace(' ', '', $uppercaseWords);
		$camelCase = lcfirst($uppercaseWords);
		Route::get('/{client}/' . $security, 'Api\Users\SecurityController@get' . $uppercaseWords);
		Route::post('/{client}/' . $security, 'Api\Users\SecurityController@set' . $uppercaseWords);
		if ( !empty($fields) ) {
			Route::post('/{client}/' . $security . '/{fieldName}/{fieldId}', 'Api\Users\SecurityController@set'  . $uppercaseWords . 'JSONFieldById');
		}
	}

	Route::get('/', 'Api\Users\UserController@index');
	Route::get('/{client}', 'Api\Users\ClientController@show');
	Route::put('/{client}', 'Api\Users\ClientController@update');

	// IP Ban
	Route::get('/{client}/ip/check', 'Api\Users\BannedController@ip_check');

	// Country Ban
	Route::get('/{client}/country/check', 'Api\Users\BannedController@country_check');

	// Live Traffic
	Route::get('/{client}/live-traffic', 'Api\Users\LiveTrafficController@index');
});

// User CRUD Api
Route::group(['prefix' => 'user', 'middleware' => 'jwt.auth'], function() {
	Route::get('/', 'Api\Users\UserController@index');
	Route::get('/{user}', 'Api\Users\UserController@show');
	Route::put('/{user}', 'Api\Users\UserController@update');
});


// Client CRUD Api
Route::group(['prefix' => 'client', 'middleware' => 'jwt.auth'], function() {
	
});