<?php

use App\LiveTraffic;
use App\Website;
use Faker\Generator as Faker;

$factory->define(LiveTraffic::class, function (Faker $faker) {
	$browsers = ['Google Chrome', 'Mozilla Firefox', 'Opera', 'Internet Explorer'];
	shuffle($browsers);
	$os = ['Windows', 'Ubuntu', 'MacOS'];
	shuffle($os);
	$devices = ['Mobile', 'Desktop', 'Tablet', 'Phablet'];
	shuffle($devices);
    return [
        'ip' => $faker->numberBetween($min = 1, $max = 255) . '.' . $faker->numberBetween($min = 1, $max = 255) . '.' . $faker->numberBetween($min = 1, $max = 255) . '.' . $faker->numberBetween($min = 1, $max = 255),
        'useragent' => $faker->userAgent,
		'browser' => current($browsers),
		'browser_code' => $faker->numberBetween($min = 1, $max = 4),
		'os' => current($os),
        'os_code' => $faker->numberBetween($min = 1, $max = 3),
        'device_type' => current($devices),
        'country' => $faker->country,
        'country_code' => $faker->countryCode,
        'request_uri' => $faker->name,
        'referer' => $faker->url,
        'bot' => $faker->numberBetween($min = 0, $max = 1),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'time' => $faker->date($format = 'H:i', $max = 'now'),
        'uniquev' => $faker->numberBetween($min = 0, $max = 1),
        'website_id' => Website::inRandomOrder()->first()->id
    ];
});
