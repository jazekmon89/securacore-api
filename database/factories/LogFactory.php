<?php

use App\Log;
use App\Website;
use Faker\Generator as Faker;

$factory->define(Log::class, function (Faker $faker) {
	$types = ['attack', 'log'];
	shuffle($types);
	$browsers = ['Google Chrome', 'Mozilla Firefox', 'Opera', 'Internet Explorer'];
	shuffle($browsers);
	$os = ['Windows', 'Ubuntu', 'MacOS'];
	shuffle($os);
	$isps = ['Globe Telecom', 'PLDT', 'Rise', 'Bayan Telecom'];
	shuffle($isps);
    return [
        'ip' => $faker->numberBetween($min = 1, $max = 255) . '.' . $faker->numberBetween($min = 1, $max = 255) . '.' . $faker->numberBetween($min = 1, $max = 255) . '.' . $faker->numberBetween($min = 1, $max = 255),
        'date' => $faker->date($format = 'Y-m-d', $max = 'now'),
        'time' => $faker->date($format = 'H:i', $max = 'now'),
        'page' => $faker->numberBetween($min = 0, $max = 1),
        'query' => '?name=' . $faker->name($gender = null),
        'type' => current($types),
        'browser_name' => current($browsers),
        'browser_code' => $faker->numberBetween($min = 1, $max = 4),
        'os_name' => current($os),
        'os_code' => $faker->numberBetween($min = 1, $max = 3),
        'country' => $faker->country,
        'country_code' => $faker->countryCode,
        'region' => $faker->state,
        'city' => $faker->city,
        'latitude' => $faker->latitude,
        'longitude' => $faker->latitude,
        'isp' => current($isps),
        'user_agent' => $faker->userAgent,
        'referer_url' => $faker->url,
        'website_id' => Website::inRandomOrder()->first()->id
    ];
});
