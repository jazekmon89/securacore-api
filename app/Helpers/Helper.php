<?php

namespace App\Helpers;

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

}