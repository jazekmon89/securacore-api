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

Route::group(['prefix' => 'api'], function () {
	// Rate-limited APIs
	Route::group(['prefix' => 'v1/client', 'middleware' => 'throttle:30,1'], function() {
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
});
