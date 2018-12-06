<?php

namespace App\Helpers;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\File;

class Helper {

	static function domainIsAlive($url)
	{
	    $curlInit = curl_init($url);
	    curl_setopt($curlInit, CURLOPT_CONNECTTIMEOUT, 10);
	    curl_setopt($curlInit, CURLOPT_HEADER, true);
	    curl_setopt($curlInit, CURLOPT_NOBODY, true);
	    curl_setopt($curlInit, CURLOPT_RETURNTRANSFER, true);
	    $response = curl_exec($curlInit);
	    curl_close($curlInit);
	    return ($response) ? true : false;
	}

	static function generatePassword() {
		$faker = Faker::create();
		return $faker->password($minLength = 8, $maxLength = 20);
	}

	static function generatePublicKey() {
		$faker = Faker::create();
		return $faker->unique()->randomNumber($nbDigits = 8);
	}

	static function getModelByTable($model_name, $access_level = 1) {
		$model_path = app_path();
		$models = File::files($model_path);
		foreach($models as $model) {
			$model = 'App\\'.substr($model->getFilename(), 0, -4);
			$model = new $model();
			if ( (
					$access_level == 1 && $model::CAN_ADMIN_SEARCH || 
					$access_level == 2 && $model::CAN_USER_SEARCH ||
					$model::CAN_PUBLIC_SEARCH
				) && strpos($model->getTable(), $model_name) !== FALSE ) {
				return $model;
			}
		}
		return null;
	}

}