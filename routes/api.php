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

Route::group(['prefix' => 'v1/client', 'middleware' => ['jwt.auth', 'throttle:30,1']], function() {
	// securities API
	$securities = [
		'content-protection',
		'ad-blocker-protection',
		'dos-protection',
		'proxy-protection',
		'sql-protection',
		'spam-protection',
		'bot-protection'
	];
	Route::get('/{client}/security', 'Api\V1\SecurityController@getSecurities');
	foreach( $securities as $security ) {
		$uppercaseWords = str_replace('-', ' ', $security);
		$uppercaseWords = ucwords($uppercaseWords);
		$uppercaseWords = str_replace(' ', '', $uppercaseWords);
		$camelCase = lcfirst($uppercaseWords);
		Route::get('/{client}/' . $security, 'Api\V1\SecurityController@get' . $uppercaseWords);
		Route::post('/{client}/' . $security, 'Api\V1\SecurityController@set' . $uppercaseWords);
	}
	Route::post('/{client}/content-protection/function/{functionId}', 'Api\V1\SecurityController@setContentProtectionByFunctionId');
});